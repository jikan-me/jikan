<?php

namespace JikanTest\Parser\Top;

use Goutte\Client;
use Jikan\Parser\Top\TopListItemParser;
use JikanTest\TestCase;

/**
 * Class TopPeopleParserTest
 */
class TopPeopleParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Top\TopListItemParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new Client($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/people.php');

        $this->parser = new TopListItemParser(
            $crawler->filterXPath('//tr[@class="ranking-list"]')->eq(7)
        );
    }

    /**
     * @test
     */
    public function it_gets_the_mal_url()
    {
        $url = $this->parser->getMalUrl();
        self::assertEquals('Oda, Eiichiro', $url);
        self::assertEquals('https://myanimelist.net/people/1881/Eiichiro_Oda', $url->getUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_rank()
    {
        self::assertEquals(8, $this->parser->getRank());
    }

    /**
     * @test
     */
    public function it_gets_the_favorites()
    {
        self::assertEquals(46179, $this->parser->getPeopleFavorites());
    }

    /**
     * @test
     */
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/voiceactors/2/10593.jpg?s=6e83dfc242f5610e419eb59c24aebdc6',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_kanji_name()
    {
        self::assertEquals(
            '尾田 栄一郎',
            $this->parser->getKanjiName()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_birthday()
    {
        self::assertEquals(
            '1975-01-01',
            $this->parser->getBirthday()->format('Y-m-d')
        );
    }
}
