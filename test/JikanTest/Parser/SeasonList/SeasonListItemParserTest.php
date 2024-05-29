<?php

namespace Jikan\Parser\SeasonList;

use JikanTest\TestCase;

class SeasonListItemParserTest extends TestCase
{
    /**
     * @var SeasonListItemParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/season/archive');
        $this->parser = new SeasonListItemParser(
            $crawler->filterXPath('//table[contains(@class, "anime-seasonal-byseason")]/tr')->first()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\SeasonList\SeasonListItemParser::getYear
     */
    public function it_gets_the_year(): void
    {
        self::assertEquals(2023, $this->parser->getYear());
    }

    /**
     * @test
     * @covers \Jikan\Parser\SeasonList\SeasonListItemParser::getSeasons
     */
    public function it_gets_the_seasons(): void
    {
        self::assertContains('winter', $this->parser->getSeasons());
    }
}
