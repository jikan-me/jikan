<?php

namespace JikanTest\Parser\Seasonal;

use Jikan\Parser\Common\AnimeCardParser;
use JikanTest\TestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class SeasonalParserTest
 */
class SeasonalAnimeParserTest extends TestCase
{
    /**
     * @var Crawler
     */
    private $crawler;
    /**
     * @var AnimeCardParser
     */
    private $parser;

    /**
     * @var AnimeCardParser
     */
    private $parser2;

    /**
     * @var AnimeCardParser
     */
    private $parserKids;

    /**
     * @var AnimeCardParser
     */
    private $parserR18;

    /**
     * @var AnimeCardParser
     */
    private $springParser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new \Goutte\Client($this->httpClient);
        $request = new \Jikan\Request\Seasonal\SeasonalRequest(2018, 'spring');
        $this->crawler = $crawler = $client->request('GET', $request->getPath());
        $this->parser = new AnimeCardParser($crawler->filter('div.seasonal-anime')->first());
    }

    /**
     * @test
     */
    public function it_gets_the_producer()
    {
        $this->parser2 = new AnimeCardParser($this->crawler->filter('div.seasonal-anime')->eq(2));
        $producer = $this->parser->getProducer();
        self::assertCount(1, $producer);
        self::assertContainsOnly(\Jikan\Model\Common\MalUrl::class, $producer);
        $producer = $producer[0];
        self::assertEquals('Bones', $producer);
        self::assertEquals('https://myanimelist.net/anime/producer/4/Bones', $producer->getUrl());

        $producer = $this->parser2->getProducer();
        self::assertCount(1, $producer);
        self::assertContainsOnly(\Jikan\Model\Common\MalUrl::class, $producer);
        self::assertContains('Pierrot Plus', $producer);
        self::assertContains('Studio Pierrot', $producer);
    }

    /**
     * @test
     */
    public function it_gets_the_episodes()
    {
        $this->parser2 = new AnimeCardParser($this->crawler->filter('div.seasonal-anime')->eq(2));
        self::assertEquals(25, $this->parser->getEpisodes());
        self::assertEquals(23, $this->parser2->getEpisodes());
    }

    /**
     * @test
     */
    public function it_gets_the_source()
    {
        $this->parser2 = new AnimeCardParser($this->crawler->filter('div.seasonal-anime')->eq(2));
        self::assertEquals('Manga', $this->parser->getSource());
        self::assertEquals('Visual novel', $this->parser2->getSource());
    }

    /**
     * @test
     */
    public function it_gets_the_genres()
    {
        $genres = $this->parser->getGenres();
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $genres);
        self::assertContains('Action', $genres[0]->getName());
        self::assertContains('Comedy', $genres[1]->getName());
        self::assertContains('Super Power', $genres[2]->getName());
    }

    /**
     * @test
     */
    public function it_gets_the_title()
    {
        self::assertEquals('Boku no Hero Academia 3rd Season', $this->parser->getTitle());
    }

    /**
     * @test
     */
    public function it_gets_the_description()
    {
        self::assertContains('As summer arrives for the students at UA Academy, each of these superheroes', $this->parser->getDescription());
    }

    /**
     * @test
     */
    public function it_gets_the_type()
    {
        self::assertEquals('TV', $this->parser->getType());
    }

    /**
     * @test
     */
    public function it_gets_the_air_dates()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->parser->getAirDates());
    }

    /**
     * @test
     */
    public function it_gets_the_air_members()
    {
        self::assertEquals(1247636, $this->parser->getMembers());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_id()
    {
        self::assertEquals(36456, $this->parser->getMalId());
    }

    /**
     * @test
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
     */
    public function it_gets_the_anime_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/anime/1319/92084.jpg',
            $this->parser->getAnimeImage()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_score()
    {
        $this->parser2 = new AnimeCardParser($this->crawler->filter('div.seasonal-anime')->eq(2));
        self::assertEquals(
            8.52,
            $this->parser2->getAnimeScore()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_licensor()
    {
        self::assertCount(1, $this->parser->getLicensors());
    }

    /**
     * @test
     */
    public function it_gets_the_r18_rating()
    {
        self::assertFalse($this->parser->isR18());
        $this->parserR18 = new AnimeCardParser($this->crawler->filter('div.seasonal-anime.r18')->first());
        self::assertTrue($this->parserR18->isR18());
    }

    /**
     * @test
     */
    public function it_gets_the_kids_rating()
    {
        self::assertFalse($this->parser->isKids());
        $this->parserKids = new AnimeCardParser($this->crawler->filter('div.seasonal-anime.kids')->first());
        self::assertTrue($this->parserKids->isKids());
    }

    /**
     * @test
     */
    public function it_gets_continuing()
    {
        $this->springParser = new AnimeCardParser(
            $this->crawler->filter(
                '#content > div.js-categories-seasonal > div:nth-child(2) > div:nth-child(2)'
            )->first()
        );
        self::assertEquals('One Piece', $this->springParser->getTitle());
        self::assertTrue($this->springParser->isContinuing());
        self::assertEquals('Boku no Hero Academia 3rd Season', $this->parser->getTitle());
        self::assertFalse($this->parser->isContinuing());
    }
}
