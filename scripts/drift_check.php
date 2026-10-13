<?php

declare(strict_types=1);

/**
 * Live drift check: fetch a small set of canary pages from myanimelist.net, parse them with the current
 * code and report anything that no longer parses or has gone suspiciously empty.
 *
 *   php scripts/drift_check.php --live                    # ~14 requests, 3.5 s apart (about a minute)
 *   php scripts/drift_check.php --live --json=drift.json  # also write machine-readable results
 *   php scripts/drift_check.php --live --compare=old.json # flag canaries whose null-rate rose >10 points
 *
 * Fixtures are recordings, so a green test suite does not prove the parsers still match the live site
 * ("fixture-green is not live-green", DECISIONS.md). This is the live half. It sends GET requests only, one at
 * a time, with a fixed gap so it stays well inside MAL's rate limits. It is NOT part of `composer test`
 * or the pre-commit gate, and --live is required so it can never run by accident.
 *
 * "null rate" is the share of scalar fields in the parsed model that are null: a rising value for the same
 * page is the early sign that a selector stopped matching (a field silently became null).
 */
require dirname(__DIR__) . '/vendor/autoload.php';

use Jikan\Exception\BadResponseException;
use Jikan\Exception\ParserException;
use Jikan\MyAnimeList\MalClient;
use Jikan\Request;

const GAP_SECONDS = 3.5;

$options = getopt('', ['live', 'json::', 'compare::', 'help']);
if (isset($options['help']) || !isset($options['live'])) {
    fwrite(STDOUT, "Live check against myanimelist.net. Re-run with --live to confirm. See the header of this file.\n");
    exit (isset($options['help']) ? 0 : 2);
}

$client = new MalClient();

/** @var array<string, callable(): object> label => request that returns the parsed model */
$canaries = [
    'anime/1 (Cowboy Bebop)' => fn () => $client->getAnime(new Request\Anime\AnimeRequest(1)),
    'anime/6 (Trigun)'       => fn () => $client->getAnime(new Request\Anime\AnimeRequest(6)),
    'anime/5114 (FMA:B)'     => fn () => $client->getAnime(new Request\Anime\AnimeRequest(5114)),
    'manga/1 (Monster)'      => fn () => $client->getManga(new Request\Manga\MangaRequest(1)),
    'manga/11 (Naruto)'      => fn () => $client->getManga(new Request\Manga\MangaRequest(11)),
    'character/1'            => fn () => $client->getCharacter(new Request\Character\CharacterRequest(1)),
    'person/1'               => fn () => $client->getPerson(new Request\Person\PersonRequest(1)),
    'club/1'                 => fn () => $client->getClub(new Request\Club\ClubRequest(1)),
    'producer/1'             => fn () => $client->getProducer(new Request\Producer\ProducerRequest(1)),
    'anime genre/1'          => fn () => $client->getAnimeGenre(new Request\Genre\AnimeGenreRequest(1)),
    'seasonal (current)'     => fn () => $client->getSeasonal(new Request\Seasonal\SeasonalRequest()),
    'schedule'               => fn () => $client->getSchedule(new Request\Schedule\ScheduleRequest()),
    'search anime "Fate"'    => fn () => $client->getAnimeSearch(new Request\Search\AnimeSearchRequest('Fate')),
    'top anime'              => fn () => $client->getTopAnime(new Request\Top\TopAnimeRequest()),
];

/** @return array{0:int,1:int} [null scalars, all scalars] across the whole model graph */
function nullStats(mixed $value, \SplObjectStorage $seen): array
{
    if (is_array($value)) {
        $stats = [0, 0];
        foreach ($value as $item) {
            [$n, $t] = nullStats($item, $seen);
            $stats[0] += $n;
            $stats[1] += $t;
        }

        return $stats;
    }
    if ($value instanceof \DateTimeInterface || !is_object($value)) {
        return [$value === null ? 1 : 0, 1];
    }
    if ($seen->contains($value)) {
        return [0, 0];
    }
    $seen->attach($value);

    $stats = [0, 0];
    for ($class = new \ReflectionObject($value); $class; $class = $class->getParentClass()) {
        foreach ($class->getProperties() as $property) {
            if ($property->isStatic() || $property->getDeclaringClass()->getName() !== $class->getName() || !$property->isInitialized($value)) {
                continue;
            }
            [$n, $t] = nullStats($property->getValue($value), $seen);
            $stats[0] += $n;
            $stats[1] += $t;
        }
    }

    return $stats;
}

$baseline = null;
if (isset($options['compare'])) {
    $decoded = json_decode((string) file_get_contents((string) $options['compare']), true);
    $baseline = is_array($decoded) ? ($decoded['results'] ?? null) : null;
}

$results = [];
$failures = 0;
$first = true;
printf("PHP %s | %d canaries | %.1fs between requests\n\n", PHP_VERSION, count($canaries), GAP_SECONDS);
printf("%-24s %-9s %8s %9s  %s\n", 'canary', 'result', 'ms', 'null rate', 'detail');
foreach ($canaries as $label => $run) {
    if (!$first) {
        usleep((int) (GAP_SECONDS * 1_000_000));
    }
    $first = false;

    $start = hrtime(true);
    try {
        $model = $run();
        $ms = round((hrtime(true) - $start) / 1e6);
        [$nulls, $total] = nullStats($model, new \SplObjectStorage());
        $rate = $total ? round(100 * $nulls / $total, 1) : 0.0;
        $detail = '';
        if ($baseline !== null && isset($baseline[$label]['null_rate']) && $rate - $baseline[$label]['null_rate'] > 10) {
            $detail = sprintf('null rate up from %.1f%%', $baseline[$label]['null_rate']);
            $failures++;
        }
        $results[$label] = ['result' => 'ok', 'ms' => $ms, 'null_rate' => $rate, 'fields' => $total];
        printf("%-24s %-9s %8d %8.1f%%  %s\n", $label, 'ok', $ms, $rate, $detail);
    } catch (BadResponseException | ParserException $e) {
        $failures++;
        $previous = $e->getPrevious();
        $results[$label] = ['result' => 'fail', 'error' => $e::class, 'message' => $e->getMessage()];
        printf("%-24s %-9s %8s %9s  %s%s\n", $label, 'FAIL', '-', '-', $e->getMessage(), $previous ? ' <- ' . $previous->getMessage() : '');
    } catch (\Throwable $e) {
        $failures++;
        $results[$label] = ['result' => 'error', 'error' => $e::class, 'message' => $e->getMessage()];
        printf("%-24s %-9s %8s %9s  %s: %s\n", $label, 'ERROR', '-', '-', $e::class, $e->getMessage());
    }
}

printf("\n%d of %d canaries need attention.\n", $failures, count($canaries));

if (isset($options['json'])) {
    file_put_contents((string) $options['json'], json_encode(['php' => PHP_VERSION, 'at' => gmdate('c'), 'results' => $results], JSON_PRETTY_PRINT) . "\n");
    fwrite(STDOUT, "wrote {$options['json']}\n");
}

exit ($failures > 0 ? 1 : 0);
