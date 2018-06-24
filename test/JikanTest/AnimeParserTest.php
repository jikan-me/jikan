<?php

use PHPUnit\Framework\TestCase;

/**
 * Class AnimeParserTest
 */
class AnimeParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Anime
     */
    private $parser;

    public function setUp()
    {
        $request = new \Jikan\Request\AnimeRequest(21);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Anime($crawler);
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_title()
    {
        self::assertEquals('One Piece', $this->parser->getAnimeTitle());
    }
}
