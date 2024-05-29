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
        self::assertEquals('Ono, Daisuke', $url->getName());
        self::assertEquals('https://myanimelist.net/people/212/Daisuke_Ono', $url->getUrl());
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
        self::assertEquals(47386, $this->parser->getPeopleFavorites());
    }

    /**
     * @test
     */
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/voiceactors/1/54593.jpg?s=3c92b9a278bb1fb08cf7f0f3f2ec7bee',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_kanji_name()
    {
        self::assertEquals(
            '小野 大輔',
            $this->parser->getKanjiName()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_birthday()
    {
        self::assertEquals(
            '1978-05-04',
            $this->parser->getBirthday()->format('Y-m-d')
        );
    }
}
