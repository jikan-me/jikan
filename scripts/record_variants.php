<?php

declare(strict_types=1);

/**
 * Record the corpus pages in test/unit/Corpus/variants.json from live MyAnimeList.
 *
 *   php scripts/record_variants.php --dry-run             # list what would be fetched; no network
 *   php scripts/record_variants.php --live                # record every variant that has no recording yet
 *   php scripts/record_variants.php --live --only=a,b     # just these variant ids
 *   php scripts/record_variants.php --live --force        # re-record even if a recording exists
 *
 * Each variant runs through its real MalClient method; every request the method makes is fetched live and saved
 * to the jikan-me/jikan-fixtures package as pages/<key>.html plus pages/<key>.meta.json (URL, final URL, HTTP
 * status, content type, recording time), so the corpus test replays exactly what the library asked for,
 * including 404s. GET only, one request at a time, at least 3.5 s apart; --live is required.
 *
 * Point vendor/jikan-me/jikan-fixtures at a local clone of jikan-fixtures first (see `composer link-fixtures`)
 * so the recordings land in a repository you can commit.
 */
require dirname(__DIR__) . '/vendor/autoload.php';

use Jikan\MyAnimeList\MalClient;
use JikanTest\Corpus\Variants;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

const GAP_SECONDS = 3.5;

$options = getopt('', ['live', 'dry-run', 'only:', 'force']);
if (!isset($options['live']) && !isset($options['dry-run'])) {
    fwrite(STDOUT, "Records live MyAnimeList pages. Use --dry-run to list them, --live to fetch. See the header of this file.\n");
    exit (2);
}

$only = isset($options['only']) ? array_flip(explode(',', (string) $options['only'])) : null;
$force = isset($options['force']);
$variants = array_values(array_filter(Variants::all(), static fn (array $v): bool => $only === null || isset($only[$v['id']])));

is_dir(Variants::PAGES) || mkdir(Variants::PAGES, 0o755, true);

if (isset($options['dry-run'])) {
    $todo = 0;
    foreach ($variants as $v) {
        if (in_array('no-request', $v['tags'] ?? [], true)) {
            continue;
        }
        $url = Variants::request($v)->getPath();
        $have = is_file(Variants::PAGES . '/' . Variants::key('GET', $url) . '.meta.json');
        $todo += ($have && !$force) ? 0 : 1;
        printf("%-36s %s %s\n", $v['id'], ($have && !$force) ? 'have' : 'NEED', $url);
    }
    printf("\n%d variants, about %d page requests to make (%.0f s at %.1f s apart)\n", count($variants), $todo, $todo * GAP_SECONDS, GAP_SECONDS);
    exit (0);
}

$live = HttpClient::create(['headers' => ['User-Agent' => 'Symfony BrowserKit']]);
$last = 0.0;
$fetched = 0;

// Every request MalClient makes goes through here: replay a recording, or fetch live and record it.
$recorder = static function (string $method, string $url) use ($live, $force, &$last, &$fetched): MockResponse {
    $base = Variants::PAGES . '/' . Variants::key($method, $url);
    if (!$force && is_file($base . '.meta.json')) {
        $meta = json_decode((string) file_get_contents($base . '.meta.json'), true);

        return new MockResponse((string) file_get_contents($base . '.html'), ['http_code' => $meta['status']]);
    }

    $wait = GAP_SECONDS - (microtime(true) - $last);
    if ($wait > 0) {
        usleep((int) ($wait * 1_000_000));
    }
    $last = microtime(true);

    $response = $live->request($method, $url);
    $status = $response->getStatusCode();
    $body = $response->getContent(false);
    $headers = $response->getHeaders(false);
    $fetched++;

    file_put_contents($base . '.html', $body);
    file_put_contents($base . '.meta.json', json_encode([
        'method' => $method,
        'url' => $url,
        'final_url' => $response->getInfo('url'),
        'status' => $status,
        'content_type' => $headers['content-type'][0] ?? null,
        'recorded_at' => gmdate('Y-m-d\TH:i:s+00:00'),
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");

    return new MockResponse($body, ['http_code' => $status]);
};

$client = new MalClient(new MockHttpClient($recorder));
foreach ($variants as $v) {
    $before = $fetched;
    try {
        $client->{$v['method']}(Variants::request($v));
        $result = 'ok';
    } catch (\Throwable $e) {
        $result = (new \ReflectionClass($e))->getShortName() . ': ' . substr($e->getMessage(), 0, 90);
    }
    printf("%-36s fetched %d  %s\n", $v['id'], $fetched - $before, $result);
}
printf("\nDone: %d page(s) fetched live.\n", $fetched);
