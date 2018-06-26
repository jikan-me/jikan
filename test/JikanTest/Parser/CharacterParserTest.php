<?php

use PHPUnit\Framework\TestCase;

/**
 * Class CharacterParserTest
 */
class CharacterParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Character
     */
    private $parser;

    public function setUp()
    {
        $request = new \Jikan\Request\Character(2);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Character($crawler);
    }

    /**
     * @test
     * @vcr CharacterParserTest.yaml
     */
    public function it_gets_the_mal_id()
    {
        self::assertEquals(2, $this->parser->getMalId());
    }

    /**
     * @test
     * @vcr CharacterParserTest.yaml
     */
    public function it_gets_the_character_url()
    {
        self::assertEquals('https://myanimelist.net/character/2/Faye_Valentine', $this->parser->getCharacterLink());
    }
}
