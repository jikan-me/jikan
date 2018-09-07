<?php

namespace JikanTest\Parser\Top;

use Goutte\Client;
use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Top\TopListItemParser;
use PHPUnit\Framework\TestCase;

/**
 * Class TopCharacterParserTest
 */
class TopCharacterParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Top\TopListItemParser
     */
    private $parser;

    public function setUp()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/character.php');

        $this->parser = new TopListItemParser(
            $crawler->filterXPath('//tr[@class="ranking-list"]')->eq(2)
        );
    }

    /**
     * @test
     * @vcr TopCharacterParserTest.yaml
     */
    public function it_gets_the_mal_url()
    {
        $url = $this->parser->getMalUrl();
        self::assertEquals('Monkey D., Luffy', $url);
        self::assertEquals('https://myanimelist.net/character/40/Luffy_Monkey_D', $url->getUrl());
    }

    /**
     * @test
     * @vcr TopCharacterParserTest.yaml
     */
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://myanimelist.cdn-dena.com/images/characters/9/310307.jpg?s=1422edf1e44c7b6262386330461eecfd',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     * @vcr TopCharacterParserTest.yaml
     */
    public function it_gets_the_rank()
    {
        self::assertEquals(3, $this->parser->getRank());
    }

    /**
     * @test
     * @vcr TopCharacterParserTest.yaml
     */
    public function it_gets_the_character_kanji()
    {
        self::assertEquals('モンキー・D・ルフィ', $this->parser->getKanjiName());
    }

    /**
     * @test
     * @vcr TopCharacterParserTest.yaml
     */
    public function it_gets_the_animeography()
    {
        self::assertCount(3, $this->parser->getAnimeography());
        self::assertContainsOnlyInstancesOf(MalUrl::class, $this->parser->getAnimeography());
    }

    /**
     * @test
     * @vcr TopCharacterParserTest.yaml
     */
    public function it_gets_the_mangaography()
    {
        self::assertCount(3, $this->parser->getMangaography());
        self::assertContainsOnlyInstancesOf(MalUrl::class, $this->parser->getMangaography());
    }

    /**
     * @test
     * @vcr TopCharacterParserTest.yaml
     */
    public function it_gets_the_favorites()
    {
        self::assertEquals(49856, $this->parser->getFavorites());
    }
}
