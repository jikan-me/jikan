<?php

namespace JikanTest;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\MockHttpClient;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    protected $httpClient;

    protected function setUp(): void
    {
        parent::setUp();

        $vcrPath = join(DIRECTORY_SEPARATOR, [__DIR__, '..', 'http.cache']);
        $this->httpClient = new MockHttpClient(new HttpFilesystemCacheClientCallback($vcrPath));
    }
}