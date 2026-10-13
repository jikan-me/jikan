<?php

namespace JikanTest\Parser\Club;

use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;

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
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Club\UserListParser($crawler);
    }

    #[Test]
    public function it_gets_users(): void
    {
        $results = $this->parser->getResults();
        self::assertCount(
            36,
            $results
        );
    }

    #[Test]
    public function it_builds_the_same_model_when_parsed_twice(): void
    {
        self::assertEquals($this->parser->getModel(), $this->parser->getModel());
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->parser->getModel());
    }
}
