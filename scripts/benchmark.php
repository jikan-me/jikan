<?php

declare(strict_types=1);

/**
 * Parse benchmark over the recorded fixtures (hermetic: no network, no live fetch).
 *
 *   php scripts/benchmark.php                       # table, 20 iterations, 3 warm-up runs
 *   php scripts/benchmark.php --iterations=50
 *   php scripts/benchmark.php --filter=anime        # only cases whose label contains "anime"
 *   php scripts/benchmark.php --json=bench.json     # also write machine-readable results
 *   php scripts/benchmark.php --compare=base.json   # print the delta against an earlier --json run
 *
 * Each case is timed in two phases, so a change to one cannot hide in the other:
 *   dom    - building the Symfony Crawler from the recorded HTML (the HTTP body is read from the fixture)
 *   parse  - Parser::getModel() on an already-built crawler (this is the code the refactors touch)
 *
 * Numbers are wall-clock on whatever machine runs this: compare runs from the SAME machine, and treat
 * differences under ~10% as noise. Memory is the peak above the starting level while parsing one page.
 */
require dirname(__DIR__) . '/vendor/autoload.php';

use Jikan\Http\HttpClientWrapper;
use Jikan\Request;
use JikanTest\HttpFilesystemCacheClientCallback;
use Symfony\Component\HttpClient\MockHttpClient;

const FIXTURES = __DIR__ . '/../vendor/jikan-me/jikan-fixtures/fixtures';

$options = getopt('', ['iterations::', 'warmup::', 'filter::', 'json::', 'compare::', 'help']);
if (isset($options['help'])) {
    fwrite(STDOUT, "See the header of scripts/benchmark.php for usage.\n");
    exit (0);
}
$iterations = max(1, (int) ($options['iterations'] ?? 20));
$warmup = max(0, (int) ($options['warmup'] ?? 3));
$filter = isset($options['filter']) ? strtolower((string) $options['filter']) : null;

/**
 * label => [fixture test class (its fixtures dir), path, parser class]
 *
 * Keep these in step with the matching *ParserTest setUp(): the fixture is addressed by
 * sha1(method + url) inside the directory named after the test class.
 */
$cases = [
    'anime'              => ['Anime\\AnimeParserTest', (new Request\Anime\AnimeRequest(6))->getPath(), Jikan\Parser\Anime\AnimeParser::class],
    'anime-characters'   => ['Anime\\CharactersAndStaffParserTest', (new Request\Anime\AnimeCharactersAndStaffRequest(1))->getPath(), Jikan\Parser\Anime\CharactersAndStaffParser::class],
    'anime-episodes'     => ['Anime\\AnimeEpisodesParserTest', (new Request\Anime\AnimeEpisodesRequest(21))->getPath(), Jikan\Parser\Anime\EpisodesParser::class],
    'manga'              => ['Manga\\MangaParserTest', (new Request\Manga\MangaRequest(11))->getPath(), Jikan\Parser\Manga\MangaParser::class],
    'character'          => ['Character\\CharacterParserTest', (new Request\Character\CharacterRequest(116281))->getPath(), Jikan\Parser\Character\CharacterParser::class],
    'person'             => ['Person\\PersonParserTest', (new Request\Person\PersonRequest(99))->getPath(), Jikan\Parser\Person\PersonParser::class],
    'club'               => ['Club\\ClubParserTest', (new Request\Club\ClubRequest(1))->getPath(), Jikan\Parser\Club\ClubParser::class],
    'reviews'            => ['Reviews\\ReviewsParserTest', (new Request\Reviews\ReviewsRequest())->getPath(), Jikan\Parser\Reviews\ReviewsParser::class],
    'genre-anime'        => ['Genre\\AnimeGenreParserTest', 'https://myanimelist.net/anime/genre/1', Jikan\Parser\Genre\AnimeGenreParser::class],
    'producer'           => ['Producer\\ProducerParserTest', 'https://myanimelist.net/anime/producer/1', Jikan\Parser\Producer\ProducerParser::class],
    'search-anime'       => ['Search\\AnimeSearchParserTest', (new Request\Search\AnimeSearchRequest('Fate'))->getPath(), Jikan\Parser\Search\AnimeSearchParser::class],
    'seasonal'           => ['Seasonal\\SeasonalParserTest', (new Request\Seasonal\SeasonalRequest(2018, 'spring'))->getPath(), Jikan\Parser\Seasonal\SeasonalParser::class],
];

/** @param list<float> $values */
function median(array $values): float
{
    sort($values);
    $n = count($values);

    return $n % 2 ? $values[intdiv($n, 2)] : ($values[$n / 2 - 1] + $values[$n / 2]) / 2;
}

$results = [];
foreach ($cases as $label => [$testClass, $path, $parserClass]) {
    if ($filter !== null && !str_contains($label, $filter)) {
        continue;
    }

    $fixtureDir = FIXTURES . '/JikanTest_Parser_' . str_replace('\\', '_', $testClass);
    if (!is_dir($fixtureDir)) {
        $results[$label] = ['skipped' => 'no fixture directory'];
        continue;
    }

    try {
        $client = new HttpClientWrapper(new MockHttpClient(new HttpFilesystemCacheClientCallback($fixtureDir)));
        $crawler = $client->request('GET', $path);
    } catch (Throwable $e) {
        $results[$label] = ['skipped' => 'fixture not loadable: ' . $e->getMessage()];
        continue;
    }
    if (!method_exists($parserClass, 'getModel')) {
        $results[$label] = ['skipped' => 'parser has no getModel()'];
        continue;
    }

    $html = $crawler->html();
    $uri = $crawler->getUri();

    // DOM build: same call the HTTP browser performs, from the already-read body.
    $dom = [];
    for ($i = 0; $i < $warmup + $iterations; $i++) {
        $t = hrtime(true);
        $c = new Symfony\Component\DomCrawler\Crawler(null, $uri);
        $c->addHtmlContent($html);
        $elapsed = (hrtime(true) - $t) / 1e6;
        if ($i >= $warmup) {
            $dom[] = $elapsed;
        }
    }

    // Parse + model on a fresh crawler per run, so no run sees another's state.
    $parse = [];
    $peak = 0;
    try {
        for ($i = 0; $i < $warmup + $iterations; $i++) {
            $c = new Symfony\Component\DomCrawler\Crawler(null, $uri);
            $c->addHtmlContent($html);
            $parser = new $parserClass($c);
            gc_collect_cycles();
            memory_reset_peak_usage();
            $before = memory_get_usage();
            $t = hrtime(true);
            $parser->getModel();
            $elapsed = (hrtime(true) - $t) / 1e6;
            if ($i >= $warmup) {
                $parse[] = $elapsed;
                $peak = max($peak, memory_get_peak_usage() - $before);
            }
        }
    } catch (Throwable $e) {
        $results[$label] = ['skipped' => 'getModel() failed: ' . $e->getMessage()];
        continue;
    }

    $results[$label] = [
        'html_kb'    => round(strlen($html) / 1024, 1),
        'dom_ms'     => round(median($dom), 2),
        'parse_ms'   => round(median($parse), 2),
        'parse_min'  => round(min($parse), 2),
        'peak_mb'    => round($peak / 1048576, 2),
    ];
}

$baseline = null;
if (isset($options['compare'])) {
    $decoded = json_decode((string) file_get_contents((string) $options['compare']), true);
    $baseline = is_array($decoded) ? ($decoded['results'] ?? null) : null;
    if ($baseline === null) {
        fwrite(STDERR, "Could not read a baseline from {$options['compare']}\n");
        exit (2);
    }
}

printf("PHP %s | iterations=%d warmup=%d | median ms\n\n", PHP_VERSION, $iterations, $warmup);
printf("%-18s %8s %9s %10s %9s %9s%s\n", 'case', 'html KB', 'dom ms', 'parse ms', 'min ms', 'peak MB', $baseline ? '   vs baseline (parse)' : '');
foreach ($results as $label => $r) {
    if (isset($r['skipped'])) {
        printf("%-18s skipped: %s\n", $label, $r['skipped']);
        continue;
    }
    $delta = '';
    if ($baseline !== null && isset($baseline[$label]['parse_ms']) && $baseline[$label]['parse_ms'] > 0) {
        $pct = ($r['parse_ms'] / $baseline[$label]['parse_ms'] - 1) * 100;
        $delta = sprintf('   %+.1f%%', $pct);
    }
    printf("%-18s %8.1f %9.2f %10.2f %9.2f %9.2f%s\n", $label, $r['html_kb'], $r['dom_ms'], $r['parse_ms'], $r['parse_min'], $r['peak_mb'], $delta);
}

if (isset($options['json'])) {
    file_put_contents((string) $options['json'], json_encode([
        'php' => PHP_VERSION,
        'iterations' => $iterations,
        'warmup' => $warmup,
        'results' => $results,
    ], JSON_PRETTY_PRINT) . "\n");
    fwrite(STDOUT, "\nwrote {$options['json']}\n");
}
