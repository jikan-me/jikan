<?php

namespace JikanTest\Parser\Top;

use Goutte\Client;
use Jikan\Parser\Top\TopListItemParser;
use PHPUnit\Framework\TestCase;

/**
 * Class TopAnimeParserTest
 */
class TopAnimeParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Top\TopListItemParser
     */
    private $parser;

    public function setUp()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/topanime.php');

        $this->parser = new TopListItemParser(
            $crawler->filterXPath('//tr[@class="ranking-list"]')->eq(10)
        );
    }

    /**
     * @test
     * @vcr TopAnimeParserTest.yaml
     */
    public function it_gets_the_mal_url()
    {
        $url = $this->parser->getMalUrl();
        self::assertEquals('Koe no Katachi', $url);
        self::assertEquals('https://myanimelist.net/anime/28851/Koe_no_Katachi', $url->getUrl());
    }

    /**
     * @test
     * @vcr TopAnimeParserTest.yaml
     */
    public function it_gets_the_rank()
    {
        self::assertEquals(11, $this->parser->getRank());
    }

    /**
     * @test
     * @vcr TopAnimeParserTest.yaml
     */
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://myanimelist.cdn-dena.com/images/anime/3/80136.jpg?s=a3b3a8039e99287c719995e564e3d084',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     * @vcr TopAnimeParserTest.yaml
     */
    public function it_gets_the_anime_score()
    {
        self::assertEquals(9.04, $this->parser->getScore());
    }

    /**
     * @test
     * @vcr TopAnimeParserTest.yaml
     */
    public function it_gets_the_anime_type()
    {
        self::assertEquals('Movie', $this->parser->getType());
    }

    /**
     * @test
     * @vcr TopAnimeParserTest.yaml
     */
    public function it_gets_the_anime_episodes()
    {
        self::assertEquals(1, $this->parser->getEpisodes());
    }

    /**
     * @test
     * @vcr TopAnimeParserTest.yaml
     */
    public function it_gets_the_anime_members()
    {
        self::assertEquals(533061, $this->parser->getMembers());
    }

    /**
     * @test
     * @vcr TopAnimeParserTest.yaml
     */
    public function it_gets_the_anime_start_date()
    {
        self::assertEquals('Sep 2016', $this->parser->getStartDate());
    }

    /**
     * @test
     * @vcr TopAnimeParserTest.yaml
     */
    public function it_gets_the_anime_end_date()
    {
        self::assertEquals('Sep 2016', $this->parser->getEndDate());
    }
}
