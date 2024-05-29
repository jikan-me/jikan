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
        $producer = $this->parser->getProducer();
        self::assertCount(1, $producer);
        self::assertContainsOnly(\Jikan\Model\Common\MalUrl::class, $producer);
        self::assertEquals('Bones', $producer[0]->getName());
        self::assertEquals('https://myanimelist.net/anime/producer/4/Bones', $producer[0]->getUrl());

        $parser2 = new AnimeCardParser($this->crawler->filter('div.seasonal-anime')->eq(2));
        $producer = $parser2->getProducer();
        self::assertCount(1, $producer);
        self::assertContainsOnly(\Jikan\Model\Common\MalUrl::class, $producer);
        self::assertEquals('A-1 Pictures', $producer[0]->getName());
    }

    /**
     * @test
     */
    public function it_gets_the_episodes()
    {
        $parser2 = new AnimeCardParser($this->crawler->filter('div.seasonal-anime')->eq(2));
        self::assertEquals(25, $this->parser->getEpisodes());
        self::assertEquals(11, $parser2->getEpisodes());
    }

    /**
     * @test
     */
    public function it_gets_the_source()
    {
        $parser2 = new AnimeCardParser($this->crawler->filter('div.seasonal-anime')->eq(2));
        self::assertEquals('Manga', $this->parser->getSource());
        self::assertEquals('Web manga', $parser2->getSource());
    }

    /**
     * @test
     */
    public function it_gets_the_genres()
    {
        $genres = $this->parser->getGenres();
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $genres);
        self::assertEquals('Action', $genres[0]->getName());
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
        self::assertStringContainsString(
            'As summer arrives for the students at UA Academy, each of these superheroes',
            $this->parser->getDescription()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_type()
    {
        self::assertNull($this->parser->getType());
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
        self::assertEquals(20000000, $this->parser->getMembers());
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
        $parser2 = new AnimeCardParser($this->crawler->filter('div.seasonal-anime')->eq(2));
        self::assertEquals(
            7.95,
            $parser2->getAnimeScore()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_licensor()
    {
        self::assertCount(0, $this->parser->getLicensors());
    }

    /**
     * @test
     */
    public function it_gets_the_r18_rating()
    {
        self::assertFalse($this->parser->isR18());
        $parserR18 = new AnimeCardParser($this->crawler->filter('div.seasonal-anime.r18')->first());
        self::assertTrue($parserR18->isR18());
    }

    /**
     * @test
     */
    public function it_gets_the_kids_rating()
    {
        self::assertFalse($this->parser->isKids());
        $parserKids = new AnimeCardParser($this->crawler->filter('div.seasonal-anime.kids')->first());
        self::assertTrue($parserKids->isKids());
    }

    /**
     * @test
     */
    public function it_gets_continuing()
    {
        $springParser = new AnimeCardParser(
            $this->crawler->filter('div.seasonal-anime-list')->eq(1)->filter(
                'div.seasonal-anime.js-seasonal-anime'
            )->first()
        );
        self::assertEquals('One Piece', $springParser->getTitle());
        self::assertTrue($springParser->isContinuing());
        self::assertEquals('Boku no Hero Academia 3rd Season', $this->parser->getTitle());
        self::assertFalse($this->parser->isContinuing());
    }
}
