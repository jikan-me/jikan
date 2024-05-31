<?php

namespace JikanTest\Parser\Anime;

use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AnimeMoreInfoParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Anime\MoreInfoParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Anime\AnimeMoreInfoRequest(21);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Anime\MoreInfoParser($crawler);
    }

    #[Test]
    public function it_gets_more_info(): void
    {
        self::assertStringContainsString(
            'Episode 492 is the second part of a two part special called Toriko x One Piece Collabo Special',
            $this->parser->getMoreInfo()
        );
    }
}
