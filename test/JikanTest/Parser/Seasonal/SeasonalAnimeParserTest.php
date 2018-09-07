<?php

namespace JikanTest\Parser\Seasonal;

use Jikan\Parser\Common\AnimeCardParser;
use PHPUnit\Framework\TestCase;
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

    public function setUp()
    {
        $client = new \Goutte\Client();
        $request = new \Jikan\Request\Seasonal\SeasonalRequest(2018, 'spring');
        $this->crawler = $crawler = $client->request('GET', $request->getPath());
        $this->parser = new AnimeCardParser($crawler->filter('div.seasonal-anime')->first());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
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
        self::assertCount(2, $producer);
        self::assertContainsOnly(\Jikan\Model\Common\MalUrl::class, $producer);
        self::assertContains('Pierrot Plus', $producer);
        self::assertContains('Studio Pierrot', $producer);
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_episodes()
    {
        $this->parser2 = new AnimeCardParser($this->crawler->filter('div.seasonal-anime')->eq(2));
        self::assertEquals(25, $this->parser->getEpisodes());
        self::assertEquals(12, $this->parser2->getEpisodes());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_source()
    {
        $this->parser2 = new AnimeCardParser($this->crawler->filter('div.seasonal-anime')->eq(2));
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
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $genres);
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
        self::assertInstanceOf(\DateTimeImmutable::class, $this->parser->getAirDates());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_air_members()
    {
        self::assertEquals(329324, $this->parser->getMembers());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_anime_id()
    {
        self::assertEquals(36456, $this->parser->getMalId());
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
            'https://myanimelist.cdn-dena.com/images/anime/1319/92084.jpg?s=174e33772872a964b6c8b7668b46c2c5',
            $this->parser->getAnimeImage()
        );
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_anime_score()
    {
        $this->parser2 = new AnimeCardParser($this->crawler->filter('div.seasonal-anime')->eq(2));
        self::assertEquals(
            7.57,
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
        $this->parserR18 = new AnimeCardParser($this->crawler->filter('div.seasonal-anime.r18')->first());
        self::assertTrue($this->parserR18->isR18());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_kids_rating()
    {
        self::assertFalse($this->parser->isKids());
        $this->parserKids = new AnimeCardParser($this->crawler->filter('div.seasonal-anime.kids')->first());
        self::assertTrue($this->parserKids->isKids());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
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
