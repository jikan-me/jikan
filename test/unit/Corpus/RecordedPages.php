<?php

namespace JikanTest\Corpus;

use Symfony\Component\Clock\Clock;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Serves recorded corpus pages to MockHttpClient, with the HTTP status they were recorded with (so 404 and other
 * error paths in MalClient run for real), and sets the clock to the recording time (so "Yesterday, 9:25 PM" means
 * the day before the page was fetched). A request with no recording fails loudly; nothing goes live.
 */
final class RecordedPages
{
    public function __invoke(string $method, string $url, array $options = []): ResponseInterface
    {
        $base = Variants::PAGES . '/' . Variants::key($method, $url);
        if (!is_file($base . '.meta.json')) {
            throw new \RuntimeException(sprintf(
                'No recorded page for %s %s (expected %s.html). Record it with `php scripts/record_variants.php --live`.',
                strtoupper($method),
                $url,
                $base
            ));
        }

        $meta = json_decode((string) file_get_contents($base . '.meta.json'), true, 512, JSON_THROW_ON_ERROR);
        Clock::set(new MockClock(new \DateTimeImmutable($meta['recorded_at'])));

        return new MockResponse((string) file_get_contents($base . '.html'), [
            'http_code' => $meta['status'],
            'response_headers' => ['content-type: ' . ($meta['content_type'] ?? 'text/html; charset=UTF-8')],
        ]);
    }
}
