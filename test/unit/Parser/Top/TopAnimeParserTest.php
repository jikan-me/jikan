<?php

namespace JikanTest\Parser\Top;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Top\TopListItemParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

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
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/topanime.php');

        $this->parser = new TopListItemParser(
            $crawler->filterXPath('//tr[@class="ranking-list"]')->eq(10)
        );
    }

    #[Test]
    public function it_gets_the_mal_url()
    {
        $url = $this->parser->getMalUrl();
        self::assertEquals('Fruits Basket: The Final', $url->getTitle());
        self::assertEquals('https://myanimelist.net/anime/42938/Fruits_Basket__The_Final', $url->getUrl());
    }

    #[Test]
    public function it_gets_the_rank()
    {
        self::assertEquals(11, $this->parser->getRank());
    }

    #[Test]
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/anime/1085/114792.jpg?s=bb4303c0804c9d5ca9fcb30b8f8e6783',
            $this->parser->getImage()
        );
    }

    #[Test]
    public function it_gets_the_anime_score()
    {
        self::assertEquals(9.02, $this->parser->getScore());
    }

    #[Test]
    public function it_gets_the_anime_type()
    {
        self::assertEquals('TV', $this->parser->getType());
    }

    #[Test]
    public function it_gets_the_anime_episodes()
    {
        self::assertEquals(13, $this->parser->getEpisodes());
    }

    #[Test]
    public function it_gets_the_anime_members()
    {
        self::assertEquals(376544, $this->parser->getMembers());
    }

    #[Test]
    public function it_gets_the_anime_start_date()
    {
        self::assertEquals('Apr 2021', $this->parser->getStartDate());
    }

    #[Test]
    public function it_gets_the_anime_end_date()
    {
        self::assertEquals('Jun 2021', $this->parser->getEndDate());
    }
}
