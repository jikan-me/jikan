<?php

namespace JikanTest\Parser\Video;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Anime\PromoListItem;
use Jikan\Model\Anime\StreamEpisodeListItem;
use Jikan\Parser\Anime\VideosParser;
use JikanTest\Parser\Video\AnimeVideoParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class AnimeVideoParserTest
 */
class AnimeVideoParserTest extends TestCase
{
    /**
     * @var AnimeVideoParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/1/_/video');
        $this->parser = new VideosParser($crawler);
    }


    #[Test]
    public function it_gets_promos()
    {
        $videos = $this->parser->getPromos();
        self::assertContainsOnlyInstancesOf(PromoListItem::class, $videos);
    }

    #[Test]
    public function it_gets_episodes()
    {
        $videos = $this->parser->getEpisodes();
        self::assertContainsOnlyInstancesOf(StreamEpisodeListItem::class, $videos);
    }
}
