<?php

namespace JikanTest\Parser\Watch;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Watch\WatchPromotionalVideosParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class WatchPromotionalVideosParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Watch\WatchPromotionalVideosParser,
 * the parser behind getRecentPromotionalVideos() (watch/promotion?p=1) and
 * getPopularPromotionalVideos() (watch/promotion/popular). The recent page is
 * parsed in setUp (it has a second page); the popular page in its own test.
 */
class WatchPromotionalVideosParserTest extends TestCase
{
    /**
     * @var WatchPromotionalVideosParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $this->parser = $this->parse('https://myanimelist.net/watch/promotion?p=1');
    }

    private function parse(string $url): WatchPromotionalVideosParser
    {
        $client = new HttpClientWrapper($this->httpClient);

        return new WatchPromotionalVideosParser($client->request('GET', $url));
    }

    #[Test]
    public function it_parses_the_recent_promotional_videos_page(): void
    {
        $results = $this->parser->getResults();
        self::assertCount(30, $results);

        $first = $results[0];
        self::assertSame('PV 1', $first->getTitle());
        self::assertSame('Shishite Ikiru SSS-kyuu Hunter', $first->getEntry()->getTitle());
        self::assertSame(64950, $first->getEntry()->getMalId());
    }

    #[Test]
    public function it_parses_the_youtube_trailer_meta(): void
    {
        $trailer = $this->parser->getResults()[0]->getTrailer();
        self::assertSame('C6ecZk2mHqA', $trailer->getYoutubeId());
        self::assertSame(
            'https://www.youtube.com/watch?v=C6ecZk2mHqA',
            $trailer->getUrl()
        );
        self::assertSame(
            'https://img.youtube.com/vi/C6ecZk2mHqA/default.jpg',
            $trailer->getImages()->getImageUrl()
        );
    }

    #[Test]
    public function it_reports_recent_pagination(): void
    {
        self::assertTrue($this->parser->getHasNextPage());
        self::assertSame(2, $this->parser->getLastVisiblePage());
    }

    #[Test]
    public function it_parses_the_popular_promotional_videos_page(): void
    {
        $parser = $this->parse('https://myanimelist.net/watch/promotion/popular');
        $results = $parser->getResults();
        self::assertCount(30, $results);

        $first = $results[0];
        self::assertSame('PV 1', $first->getTitle());
        self::assertSame('Shingeki no Kyojin', $first->getEntry()->getTitle());
        self::assertSame(16498, $first->getEntry()->getMalId());
        self::assertSame('KKzmOh4SuBc', $first->getTrailer()->getYoutubeId());

        self::assertFalse($parser->getHasNextPage());
        self::assertSame(1, $parser->getLastVisiblePage());
    }
}