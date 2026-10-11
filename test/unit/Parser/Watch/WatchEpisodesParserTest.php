<?php

namespace JikanTest\Parser\Watch;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Watch\WatchEpisodesParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class WatchEpisodesParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Watch\WatchEpisodesParser, the
 * parser behind getRecentEpisodes() (watch/episode) and getPopularEpisodes()
 * (watch/episode/popular). The recent page is parsed in setUp; the popular
 * page is parsed in its own test.
 */
class WatchEpisodesParserTest extends TestCase
{
    /**
     * @var WatchEpisodesParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $this->parser = $this->parse('https://myanimelist.net/watch/episode');
    }

    private function parse(string $url): WatchEpisodesParser
    {
        $client = new HttpClientWrapper($this->httpClient);

        return new WatchEpisodesParser($client->request('GET', $url));
    }

    #[Test]
    public function it_parses_the_recently_aired_episodes_page(): void
    {
        $results = $this->parser->getResults();
        self::assertCount(6, $results);

        $first = $results[0];
        self::assertSame('Yamato yo, Towa ni: Rebel 3199', $first->getEntry()->getTitle());
        self::assertSame(50855, $first->getEntry()->getMalId());
        self::assertTrue($first->isRegionLocked());
    }

    #[Test]
    public function it_parses_the_recent_episode_links(): void
    {
        $episodes = $this->parser->getResults()[0]->getEpisodes();
        self::assertCount(2, $episodes);
        self::assertSame('Episode 22', $episodes[0]->getTitle());
        self::assertSame(22, $episodes[0]->getMalId());
        self::assertStringStartsWith(
            'https://myanimelist.net/anime/50855/',
            $episodes[0]->getUrl()
        );
        self::assertStringEndsWith('/episode/22', $episodes[0]->getUrl());
    }

    #[Test]
    public function it_parses_the_popular_episodes_page(): void
    {
        $results = $this->parse('https://myanimelist.net/watch/episode/popular')->getResults();
        self::assertCount(82, $results);

        $first = $results[0];
        self::assertSame('Cowboy Bebop', $first->getEntry()->getTitle());
        self::assertSame(1, $first->getEntry()->getMalId());
        self::assertSame('Episode 26', $first->getEpisodes()[0]->getTitle());
        self::assertSame(26, $first->getEpisodes()[0]->getMalId());
    }

    #[Test]
    public function it_parses_episode_artwork(): void
    {
        // The first card is region-locked and MAL shows a "banned youtube" icon
        // for it; find a card with real artwork.
        $artwork = null;
        foreach ($this->parser->getResults() as $result) {
            $image = $result->getEntry()->getImages()->getJpg()->getImageUrl();
            if ($image !== null && !str_contains($image, 'icon-banned-youtube-rect.png')) {
                $artwork = $image;
                break;
            }
        }

        self::assertNotNull($artwork);
        self::assertMatchesRegularExpression('~https://cdn\.myanimelist\.net/images/anime/.*~', $artwork);
    }
}