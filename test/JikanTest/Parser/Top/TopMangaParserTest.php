<?php

namespace JikanTest\Parser\Top;

use Goutte\Client;
use Jikan\Parser\Top\TopListItemParser;
use PHPUnit\Framework\TestCase;
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
        $client = new Client();
        $this->crawler = $crawler = $client->request('GET', 'https://myanimelist.net/topmanga.php');

        $this->parser = new TopListItemParser(
            $crawler->filterXPath('//tr[@class="ranking-list"]')->eq(5)
        );
    }

    /**
     * @test
     * @vcr TopMangaParserTest.yaml
     */
    public function it_gets_the_mal_url()
    {
        $url = $this->parser->getMalUrl();
        self::assertEquals('Vagabond', $url);
        self::assertEquals('https://myanimelist.net/manga/656/Vagabond', $url->getUrl());
    }

    /**
     * @test
     * @vcr TopMangaParserTest.yaml
     */
    public function it_gets_the_rank()
    {
        self::assertEquals(6, $this->parser->getRank());
    }

    /**
     * @test
     * @vcr TopMangaParserTest.yaml
     */
    public function it_gets_the_manga_score()
    {
        self::assertEquals(9.09, $this->parser->getScore());
    }

    /**
     * @test
     * @vcr TopMangaParserTest.yaml
     */
    public function it_gets_the_manga_type()
    {
        self::assertEquals('Manga', $this->parser->getType());
    }

    /**
     * @test
     * @vcr TopMangaParserTest.yaml
     */
    public function it_gets_the_manga_volumes()
    {
        $parser2 = new TopListItemParser(
            $this->crawler->filterXPath('//tr[@class="ranking-list"]')->eq(1)
        );
        self::assertEquals(37, $this->parser->getVolumes());
    }

    /**
     * @test
     * @vcr TopMangaParserTest.yaml
     */
    public function it_gets_the_manga_members()
    {
        self::assertEquals(160324, $this->parser->getMembers());
    }

    /**
     * @test
     * @vcr TopMangaParserTest.yaml
     */
    public function it_gets_the_manga_start_date()
    {
        self::assertEquals('Sep 1998', $this->parser->getStartDate());
    }

    /**
     * @test
     * @vcr TopMangaParserTest.yaml
     */
    public function it_gets_the_manga_end_date()
    {
        self::assertEquals('May 2015', $this->parser->getEndDate());
    }

    /**
     * @test
     * @vcr TopMangaParserTest.yaml
     */
    public function it_gets_the_manga_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/manga/2/181787.jpg?s=bbd3ff81b5d8e50781531c60cd68773f',
            $this->parser->getImage()
        );
    }
}
