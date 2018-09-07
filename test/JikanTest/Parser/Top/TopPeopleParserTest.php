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

    public function setUp()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/people.php');

        $this->parser = new TopListItemParser(
            $crawler->filterXPath('//tr[@class="ranking-list"]')->eq(7)
        );
    }

    /**
     * @test
     * @vcr TopPeopleParserTest.yaml
     */
    public function it_gets_the_mal_url()
    {
        $url = $this->parser->getMalUrl();
        self::assertEquals('Sugita, Tomokazu', $url);
        self::assertEquals('https://myanimelist.net/people/2/Tomokazu_Sugita', $url->getUrl());
    }

    /**
     * @test
     * @vcr TopPeopleParserTest.yaml
     */
    public function it_gets_the_rank()
    {
        self::assertEquals(8, $this->parser->getRank());
    }

    /**
     * @test
     * @vcr TopPeopleParserTest.yaml
     */
    public function it_gets_the_favorites()
    {
        self::assertEquals(24588, $this->parser->getPeopleFavorites());
    }

    /**
     * @test
     * @vcr TopPeopleParserTest.yaml
     */
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://myanimelist.cdn-dena.com/images/voiceactors/3/42163.jpg?s=e7aa2685616307adf04f3d1255e4dba3',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     * @vcr TopPeopleParserTest.yaml
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
     * @vcr TopPeopleParserTest.yaml
     */
    public function it_gets_the_birthday()
    {
        self::assertEquals(
            '1980-10-11',
            $this->parser->getBirthday()->format('Y-m-d')
        );
    }
}
