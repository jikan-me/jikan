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

    public function setUp(): void
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
        self::assertEquals('Gintama: The Final', $url);
        self::assertEquals('https://myanimelist.net/anime/39486/Gintama__The_Final', $url->getUrl());
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
            'https://cdn.myanimelist.net/images/anime/1027/109706.jpg?s=29712c4254f5acc66580a2107fe6643d',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     * @vcr TopAnimeParserTest.yaml
     */
    public function it_gets_the_anime_score()
    {
        self::assertEquals(9.0, $this->parser->getScore());
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
        self::assertEquals(22172, $this->parser->getMembers());
    }

    /**
     * @test
     * @vcr TopAnimeParserTest.yaml
     */
    public function it_gets_the_anime_start_date()
    {
        self::assertEquals('Jan 2021', $this->parser->getStartDate());
    }

    /**
     * @test
     * @vcr TopAnimeParserTest.yaml
     */
    public function it_gets_the_anime_end_date()
    {
        self::assertEquals('Jan 2021', $this->parser->getEndDate());
    }
}
