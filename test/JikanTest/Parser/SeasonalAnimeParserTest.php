<?php

use PHPUnit\Framework\TestCase;

/**
 * Class SeasonalParserTest
 */
class SeasonalAnimeParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\SeasonalAnime
     */
    private $parser;

    /**
     * @var \Jikan\Parser\SeasonalAnime
     */
    private $parser2;

    public function setUp()
    {
        $request = new \Jikan\Request\Seasonal(2018, 'summer');
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\SeasonalAnime($crawler->filter('div.seasonal-anime')->first());
        $this->parser2 = new \Jikan\Parser\SeasonalAnime($crawler->filter('div.seasonal-anime')->eq(2));
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_studio()
    {
        self::assertEquals('Wit Studio', $this->parser->getStudio());
        self::assertEquals('Production I.G', $this->parser2->getStudio());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_episodes()
    {
        self::assertEquals(null, $this->parser->getEpisodes());
        self::assertEquals(6, $this->parser2->getEpisodes());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_source()
    {
        self::assertEquals('Manga', $this->parser->getSource());
        self::assertEquals('Original', $this->parser2->getSource());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_genres()
    {
        $genres = $this->parser->getGenres();
        self::assertContains('Action', $genres);
        self::assertContains('Military', $genres);
        self::assertContains('Mystery', $genres);
        self::assertContains('Super Power', $genres);
        self::assertContains('Drama', $genres);
        self::assertContains('Fantasy', $genres);
        self::assertContains('Shounen', $genres);
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_title()
    {
        self::assertEquals('Shingeki no Kyojin Season 3', $this->parser->getTitle());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_description()
    {
        self::assertEquals('Third season of the Shingeki no Kyojin anime series.', $this->parser->getDescription());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_type()
    {
        self::assertEquals('TV', $this->parser->getType());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_air_dates()
    {
        self::assertEquals('Jul 23, 2018, 00:35 (JST)', $this->parser->getAirDates());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_air_members()
    {
        self::assertEquals(188048, $this->parser->getMembers());
    }
}
