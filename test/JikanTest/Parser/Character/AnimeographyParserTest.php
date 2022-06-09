<?php

namespace JikanTest\Parser\Character;

use JikanTest\TestCase;

/**
 * Class AnimeographyParserTest
 */
class AnimeographyParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Character\AnimeographyParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/character/116281');
        $crawler = $crawler->filterXPath('//div[contains(text(), \'Animeography\')]/../table/tr')->first();
        $this->parser = new \Jikan\Parser\Character\AnimeographyParser($crawler);
    }

    /**
     * @test
     */
    public function it_gets_the_anime_mal_id()
    {
        self::assertEquals(29803, $this->parser->getMalId());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_url()
    {
        self::assertEquals('https://myanimelist.net/anime/29803/Overlord', $this->parser->getUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_name()
    {
        self::assertEquals('Overlord', $this->parser->getName());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/anime/7/88019.jpg?s=5a069ff3bdeebefc62a334e9a3a41c18',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_role()
    {
        self::assertEquals('Main',$this->parser->getRole());
    }
}
