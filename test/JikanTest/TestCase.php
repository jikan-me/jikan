<?php

namespace JikanTest;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var HttpClientInterface
     */
    protected HttpClientInterface|MockHttpClient $httpClient;

    protected function setUp(): void
    {
        parent::setUp();

        $testClass = str_replace('\\', '_', get_class($this));
        $cachePath = join(
            DIRECTORY_SEPARATOR,
            [__DIR__, '..', '..', 'vendor','jikan-me', 'jikan-fixtures', 'fixtures', $testClass]
        );
        $this->httpClient = new MockHttpClient(new HttpFilesystemCacheClientCallback($cachePath));
    }
}
