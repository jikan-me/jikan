<?php

namespace JikanTest\Parser\Video;

use Goutte\Client;
use Jikan\Model\Anime\PromoListItem;
use Jikan\Model\Anime\StreamEpisodeListItem;
use Jikan\Parser\Anime\VideosParser;
use PHPUnit\Framework\TestCase;

/**
 * Class AnimeVideoParserTest
 */
class AnimeVideoParserTest extends TestCase
{
    /**
     * @var AnimeVideoParser
     */
    private $parser;

    public function setUp()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/1/_/video');
        $this->parser = new VideosParser($crawler);
    }


    /**
     * @test
     * @vcr AnimeVideoParserTest.yaml
     */
    public function it_gets_promos()
    {
        $videos = $this->parser->getPromos();
        self::assertContainsOnlyInstancesOf(PromoListItem::class, $videos);
    }

    /**
     * @test
     * @vcr AnimeVideoParserTest.yaml
     */
    public function it_gets_episodes()
    {
        $videos = $this->parser->getEpisodes();
        self::assertContainsOnlyInstancesOf(StreamEpisodeListItem::class, $videos);
    }
}
