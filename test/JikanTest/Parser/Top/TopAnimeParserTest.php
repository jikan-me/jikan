<?php

namespace JikanTest\Parser\Top;

use Goutte\Client;
use Jikan\Parser\Top\TopListItemParser;
use JikanTest\TestCase;

/**
 * Class TopAnimeParserTest
 */
class TopAnimeParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Top\TopListItemParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new Client($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/topanime.php');

        $this->parser = new TopListItemParser(
            $crawler->filterXPath('//tr[@class="ranking-list"]')->eq(10)
        );
    }

    /**
     * @test
     */
    public function it_gets_the_mal_url()
    {
        $url = $this->parser->getMalUrl();
        self::assertEquals('Ginga Eiyuu Densetsu', $url->getTitle());
        self::assertEquals('https://myanimelist.net/anime/820/Ginga_Eiyuu_Densetsu', $url->getUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_rank()
    {
        self::assertEquals(11, $this->parser->getRank());
    }

    /**
     * @test
     */
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/anime/13/13225.jpg?s=385cedad342e284c5765833ab1cddc1c',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_score()
    {
        self::assertEquals(9.03, $this->parser->getScore());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_type()
    {
        self::assertEquals('OVA', $this->parser->getType());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_episodes()
    {
        self::assertEquals(110, $this->parser->getEpisodes());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_members()
    {
        self::assertEquals(279994, $this->parser->getMembers());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_start_date()
    {
        self::assertEquals('Jan 1988', $this->parser->getStartDate());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_end_date()
    {
        self::assertEquals('Mar 1997', $this->parser->getEndDate());
    }
}
