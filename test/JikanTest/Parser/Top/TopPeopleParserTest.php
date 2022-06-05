<?php

namespace JikanTest\Parser\Top;

use Goutte\Client;
use Jikan\Parser\Top\TopListItemParser;
use PHPUnit\Framework\TestCase;

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
        $client = new Client();
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
        self::assertEquals('Sugita, Tomokazu', $url);
        self::assertEquals('https://myanimelist.net/people/2/Tomokazu_Sugita', $url->getUrl());
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
        self::assertEquals(39630, $this->parser->getPeopleFavorites());
    }

    /**
     * @test
     */
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/voiceactors/2/60638.jpg?s=5b39d822cfe1fe7c5dd164a0d4684a41',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_kanji_name()
    {
        self::assertEquals(
            '杉田 智和',
            $this->parser->getKanjiName()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_birthday()
    {
        self::assertEquals(
            '1980-10-11',
            $this->parser->getBirthday()->format('Y-m-d')
        );
    }
}
