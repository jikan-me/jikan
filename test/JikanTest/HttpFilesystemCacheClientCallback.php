<?php

namespace JikanTest;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class HttpFilesystemCacheClientCallback
{
    private $vcrPath;
    private $httpClient;

    public function __construct($vcrPath, HttpClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?? HttpClient::create();
        if (!is_dir($vcrPath)) {
            if (false === @mkdir($vcrPath, 0755, true)) {
                throw new \RuntimeException(sprintf('Unable to create the %s directory', $vcrPath));
            }
        }
        $this->vcrPath = $vcrPath;
    }

    public function __invoke(string $method, string $url, array $options = []): ResponseInterface
    {
        $hash = sha1(json_encode(compact('method', 'url', 'options')));

        $filePath = join(DIRECTORY_SEPARATOR, [$this->vcrPath, $hash]);
        if (!file_exists($filePath)) {
            $response = $this->httpClient->request($method, $url, $options);
            file_put_contents($filePath, $response->getContent());
        }
        $data = file_get_contents($filePath);
        return new MockResponse($data);
    }
}