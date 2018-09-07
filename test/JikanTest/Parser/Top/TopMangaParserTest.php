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

    public function setUp()
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
        self::assertEquals('One Piece', $url);
        self::assertEquals('https://myanimelist.net/manga/13/One_Piece', $url->getUrl());
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
        self::assertEquals(9.03, $this->parser->getScore());
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
        self::assertNull($this->parser->getVolumes());
        self::assertEquals(24, $parser2->getVolumes());
    }

    /**
     * @test
     * @vcr TopMangaParserTest.yaml
     */
    public function it_gets_the_manga_members()
    {
        self::assertEquals(212645, $this->parser->getMembers());
    }

    /**
     * @test
     * @vcr TopMangaParserTest.yaml
     */
    public function it_gets_the_manga_start_date()
    {
        self::assertEquals('Jul 1997', $this->parser->getStartDate());
    }

    /**
     * @test
     * @vcr TopMangaParserTest.yaml
     */
    public function it_gets_the_manga_end_date()
    {
        self::assertEquals('', $this->parser->getEndDate());
    }

    /**
     * @test
     * @vcr TopMangaParserTest.yaml
     */
    public function it_gets_the_manga_image()
    {
        self::assertEquals(
            'https://myanimelist.cdn-dena.com/images/manga/3/55539.jpg?s=b4d9e935b7152f0c9e69b34a7797fe02',
            $this->parser->getImage()
        );
    }
}
