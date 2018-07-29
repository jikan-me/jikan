<?php

namespace JikanTest\Parser\Anime;

use PHPUnit\Framework\TestCase;

class AnimeMoreInfoParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Anime\MoreInfoParser
     */
    private $parser;

    public function setUp()
    {
        $request = new \Jikan\Request\Anime\AnimeMoreInfoRequest(21);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Anime\MoreInfoParser($crawler);
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\MoreInfoParser
     * @vcr AnimeMoreInfoParserTest.yaml
     */
    public function it_gets_more_info(): void
    {
        self::assertContains(
            'Episode 492 is the second part of a two part special called Toriko x One Piece Collabo Special',
            $this->parser->getMoreInfo()
        );
    }
}