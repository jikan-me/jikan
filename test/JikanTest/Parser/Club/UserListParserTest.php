<?php

namespace JikanTest\Parser\Club;

use JikanTest\TestCase;

class UserListParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Club\UserListParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Club\UserListRequest(21349);
        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Club\UserListParser($crawler);
    }

    /**
     * @test
     * @covers \Jikan\Parser\Club\UserListParser
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