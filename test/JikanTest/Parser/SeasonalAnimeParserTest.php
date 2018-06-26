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

    /**
     * @var \Jikan\Parser\SeasonalAnime
     */
    private $parserKids;

    /**
     * @var \Jikan\Parser\SeasonalAnime
     */
    private $parserR18;

    /**
     * @var \Jikan\Parser\SeasonalAnime
     */
    private $springParser;

    public function setUp()
    {
        $client = new \Goutte\Client();
        $request = new \Jikan\Request\Seasonal(2018, 'spring');
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\SeasonalAnime($crawler->filter('div.seasonal-anime')->first());
        $this->parser2 = new \Jikan\Parser\SeasonalAnime($crawler->filter('div.seasonal-anime')->eq(2));
        $this->parserKids = new \Jikan\Parser\SeasonalAnime($crawler->filter('div.seasonal-anime.kids')->first());
        $this->parserR18 = new \Jikan\Parser\SeasonalAnime($crawler->filter('div.seasonal-anime.r18')->first());
        $this->springParser = new \Jikan\Parser\SeasonalAnime(
            $crawler->filter(
                '#content > div.js-categories-seasonal > div:nth-child(2) > div:nth-child(2)'
            )->first()
        );
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_producer()
    {
        $producer = $this->parser->getProducer();
        self::assertCount(1, $producer);
        self::assertContainsOnly(\Jikan\Model\MalUrl::class, $producer);
        $producer = $producer[0];
        self::assertEquals('Bones', $producer);
        self::assertEquals('https://myanimelist.net/anime/producer/4/Bones', $producer->getUrl());

        $producer = $this->parser2->getProducer();
        self::assertCount(2, $producer);
        self::assertContainsOnly(\Jikan\Model\MalUrl::class, $producer);
        self::assertContains('Pierrot Plus', $producer);
        self::assertContains('Studio Pierrot', $producer);
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_episodes()
    {
        self::assertEquals(25, $this->parser->getEpisodes());
        self::assertEquals(12, $this->parser2->getEpisodes());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_source()
    {
        self::assertEquals('Manga', $this->parser->getSource());
        self::assertEquals('Manga', $this->parser2->getSource());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_genres()
    {
        $genres = $this->parser->getGenres();
        self::assertContainsOnlyInstancesOf(\Jikan\Model\MalUrl::class, $genres);
        self::assertContains('Action', $genres);
        self::assertContains('Comedy', $genres);
        self::assertContains('School', $genres);
        self::assertContains('Shounen', $genres);
        self::assertContains('Super Power', $genres);
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_title()
    {
        self::assertEquals('Boku no Hero Academia 3rd Season', $this->parser->getTitle());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_description()
    {
        self::assertEquals('Third season of Boku no Hero Academia.', $this->parser->getDescription());
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
        self::assertEquals('Apr 7, 2018, 17:30 (JST)', $this->parser->getAirDates());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_air_members()
    {
        self::assertEquals(326216, $this->parser->getMembers());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_anime_id()
    {
        self::assertEquals(36456, $this->parser->getAnimeId());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_anime_url()
    {
        self::assertEquals(
            'https://myanimelist.net/anime/36456/Boku_no_Hero_Academia_3rd_Season',
            $this->parser->getAnimeUrl()
        );
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_anime_image()
    {
        self::assertEquals(
            'https://myanimelist.cdn-dena.com/r/167x242/images/anime/1319/92084.jpg?s=174e33772872a964b6c8b7668b46c2c5',
            $this->parser->getAnimeImage()
        );
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_anime_score()
    {
        self::assertEquals(
            7.61,
            $this->parser2->getAnimeScore()
        );
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_anime_licensor()
    {
        self::assertCount(1, $this->parser->getLicensors());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_r18_rating()
    {
        self::assertFalse($this->parser->isR18());
        self::assertTrue($this->parserR18->isR18());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_kids_rating()
    {
        self::assertFalse($this->parser->isKids());
        self::assertTrue($this->parserKids->isKids());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_continuing()
    {
        self::assertEquals('One Piece', $this->springParser->getTitle());
        self::assertTrue($this->springParser->isContinuing());
        self::assertEquals('Boku no Hero Academia 3rd Season', $this->parser->getTitle());
        self::assertFalse($this->parser->isContinuing());
    }
}
