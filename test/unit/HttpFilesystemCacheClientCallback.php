<?php

namespace JikanTest;

use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Serves recorded fixtures to MockHttpClient.
 *
 * This class is a pure cache READER: a request whose hash has no recording
 * raises loudly instead of fetching the URL live. The recordings are the
 * `jikan-me/jikan-fixtures` package (vendor/jikan-me/jikan-fixtures/fixtures),
 * refreshed with:
 *
 *     composer link-fixtures
 *
 * A live fallback was removed on purpose (backend-quality-suite
 * audit_test_network_coupling): a suite that silently fetches when a
 * recording is missing looks offline while it hits the real site — hermetic
 * only as long as nobody forgets the setup step. A missing recording is now
 * a test failure with the reason stated, never a quiet network call.
 */
class HttpFilesystemCacheClientCallback
{
    /**
     * @var string
     */
    private string $cachePath;

    /**
     * @param string $cachePath
     */
    public function __construct(string $cachePath)
    {
        if (!is_dir($cachePath)) {
            if (false === @mkdir($cachePath, 0o755, true)) {
                throw new \RuntimeException(sprintf('Unable to create the %s directory', $cachePath));
            }
        }
        $this->cachePath = $cachePath;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws \RuntimeException when the request has no recorded fixture
     */
    public function __invoke(string $method, string $url, array $options = []): ResponseInterface
    {
        $hash = sha1(json_encode(compact('method', 'url')));

        $filePath = join(DIRECTORY_SEPARATOR, [$this->cachePath, $hash]);
        if (!file_exists($filePath)) {
            throw new \RuntimeException(sprintf(
                'No recorded fixture for %s %s (expected %s). The test cache cannot go '
                . 'live: run `composer link-fixtures` to fetch the recordings from '
                . 'jikan-me/jikan-fixtures, or record this request into that package.',
                strtoupper($method),
                $url,
                $filePath
            ));
        }
        $data = file_get_contents($filePath);
        return new MockResponse($data);
    }
}
