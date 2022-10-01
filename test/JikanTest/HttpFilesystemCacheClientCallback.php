<?php

namespace JikanTest;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 *
 */
class HttpFilesystemCacheClientCallback
{
    /**
     * @var string
     */
    private string $cachePath;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @param string $cachePath
     * @param HttpClientInterface|null $httpClient
     */
    public function __construct(string $cachePath, HttpClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?? HttpClient::create();
        if (!is_dir($cachePath)) {
            if (false === @mkdir($cachePath, 0755, true)) {
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
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function __invoke(string $method, string $url, array $options = []): ResponseInterface
    {
        $hash = sha1(json_encode(compact('method', 'url', 'options')));

        $filePath = join(DIRECTORY_SEPARATOR, [$this->cachePath, $hash]);
        if (!file_exists($filePath)) {
            $response = $this->httpClient->request($method, $url, $options);
            file_put_contents($filePath, $response->getContent());
        }
        $data = file_get_contents($filePath);
        return new MockResponse($data);
    }
}
