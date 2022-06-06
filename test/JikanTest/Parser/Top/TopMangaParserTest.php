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
        self::assertEquals('Fullmetal Alchemist', $url->getTitle());
        self::assertEquals('https://myanimelist.net/manga/25/Fullmetal_Alchemist', $url->getUrl());
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
        self::assertEquals(27, $this->parser->getVolumes());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_members()
    {
        self::assertEquals(260591, $this->parser->getMembers());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_start_date()
    {
        self::assertEquals('Jul 2001', $this->parser->getStartDate());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_end_date()
    {
        self::assertEquals('Sep 2010', $this->parser->getEndDate());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/manga/3/243675.jpg?s=8cb0a643f8a7597514447f2dd0e4ffc2',
            $this->parser->getImage()
        );
    }
}
