<?php

namespace JikanTest\Parser\Character;

use PHPUnit\Framework\TestCase;

/**
 * Class AnimeographyParserTest
 */
class AnimeographyParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Character\AnimeographyParser
     */
    private $parser;

    public function setUp()
    {
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/character/116281');
        $crawler = $crawler->filterXPath('//div[contains(text(), \'Animeography\')]/../table/tr')->first();
        $this->parser = new \Jikan\Parser\Character\AnimeographyParser($crawler);
    }

    /**
     * @test
     * @vcr CharacterParserTest.yaml
     */
    public function it_gets_the_anime_mal_id()
    {
        self::assertEquals(29803, $this->parser->getMalId());
    }

    /**
     * @test
     * @vcr CharacterParserTest.yaml
     */
    public function it_gets_the_anime_url()
    {
        self::assertEquals('https://myanimelist.net/anime/29803/Overlord', $this->parser->getUrl());
    }

    /**
     * @test
     * @vcr CharacterParserTest.yaml
     */
    public function it_gets_the_anime_name()
    {
        self::assertEquals('Overlord', $this->parser->getName());
    }

    /**
     * @test
     * @vcr CharacterParserTest.yaml
     */
    public function it_gets_the_anime_image()
    {
        self::assertEquals(
            'https://myanimelist.cdn-dena.com/images/anime/7/88019.jpg?s=ff141fea6f6c523c7205ff1957340003',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     * @vcr CharacterParserTest.yaml
     */
    public function it_gets_the_role()
    {
        self::assertEquals('Main',$this->parser->getRole());
    }
}
