<?php

namespace JikanTest\Parser\Anime;

use PHPUnit\Framework\TestCase;
use VCR\VCR;

class UserListParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Club\UserListParser
     */
    private $parser;

    public function setUp()
    {
        $request = new \Jikan\Request\Club\UserListRequest(21349);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Club\UserListParser($crawler);
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     * @vcr ClubUserListParserTest.yaml
     */
    public function it_gets_users(): void
    {
        $results = $this->parser->getResults();
        self::assertCount(
            36,
            $results
        );
    }
}