<?php

namespace JikanTest\Parser\SeasonList;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\SeasonList\SeasonListItemParser;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;

class SeasonListItemParserTest extends TestCase
{
    /**
     * @var SeasonListItemParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/season/archive');
        $this->parser = new SeasonListItemParser($crawler);
    }

    #[Test]
    public function it_gets_the_year(): void
    {
        self::assertEquals(2026, $this->parser->getYear());
    }

    #[Test]
    public function it_gets_the_seasons(): void
    {
        self::assertContains('winter', $this->parser->getSeasons());
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
