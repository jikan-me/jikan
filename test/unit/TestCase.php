<?php
namespace JikanTest;

use Symfony\Component\Clock\Clock;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\Clock\NativeClock;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 *
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * "Now" for every test. MyAnimeList shows relative dates ("Yesterday, 9:25 PM", "5 hours ago", a weekday),
     * which Jikan\Helper\Time resolves against the clock; freezing it makes parsed models, and therefore their
     * snapshots, identical on every run, day and machine.
     */
    public const FROZEN_NOW = '2026-10-12T12:00:00+00:00';

    /**
     * @var HttpClientInterface|MockHttpClient
     */
    protected HttpClientInterface|MockHttpClient $httpClient;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Clock::set(new MockClock(new \DateTimeImmutable(self::FROZEN_NOW)));

        $testClass = str_replace('\\', '_', get_class($this));
        $cachePath = join(
            DIRECTORY_SEPARATOR,
            [__DIR__, '..', '..', 'vendor','jikan-me', 'jikan-fixtures', 'fixtures', $testClass]
        );
        $this->httpClient = new MockHttpClient(new HttpFilesystemCacheClientCallback($cachePath));
    }

    protected function tearDown(): void
    {
        Clock::set(new NativeClock());

        parent::tearDown();
    }
}
