<?php

namespace JikanTest\Parser\Top;

use Goutte\Client;
use Jikan\Parser\Top\TopListItemParser;
use JikanTest\TestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TopMangaParserTest
 */
class TopMangaParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Top\TopListItemParser
     */
    private $parser;

    /**
     * @var Crawler
     */
    private $crawler;

    public function setUp(): void
    {
        parent::setUp();

        $client = new Client($this->httpClient);
        $this->crawler = $crawler = $client->request('GET', 'https://myanimelist.net/topmanga.php');

        $this->parser = new TopListItemParser(
            $crawler->filterXPath('//tr[@class="ranking-list"]')->eq(5)
        );
    }

    /**
     * @test
     */
    public function it_gets_the_mal_url()
    {
        $url = $this->parser->getMalUrl();
        self::assertEquals('Slam Dunk', $url->getTitle());
        self::assertEquals('https://myanimelist.net/manga/51/Slam_Dunk', $url->getUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_rank()
    {
        self::assertEquals(6, $this->parser->getRank());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_score()
    {
        self::assertEquals(9.06, $this->parser->getScore());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_type()
    {
        self::assertEquals('Manga', $this->parser->getType());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_volumes()
    {
        $parser2 = new TopListItemParser(
            $this->crawler->filterXPath('//tr[@class="ranking-list"]')->eq(1)
        );
        self::assertEquals(31, $this->parser->getVolumes());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_members()
    {
        self::assertEquals(138611, $this->parser->getMembers());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_start_date()
    {
        self::assertEquals('Sep 1990', $this->parser->getStartDate());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_end_date()
    {
        self::assertEquals('Jun 1996', $this->parser->getEndDate());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/manga/2/258749.jpg?s=fad0d2cae56806beefaca50b445fa0dd',
            $this->parser->getImage()
        );
    }
}
