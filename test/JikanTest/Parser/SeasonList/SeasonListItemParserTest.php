<?php

namespace Jikan\Parser\SeasonList;

use PHPUnit\Framework\TestCase;

class SeasonListItemParserTest extends TestCase
{
    /**
     * @var SeasonListItemParser
     */
    private $parser;

    public function setUp()
    {
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/season/archive');
        $this->parser = new SeasonListItemParser(
            $crawler->filterXPath('//table[contains(@class, "anime-seasonal-byseason")]/tr')->first()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\SeasonList\SeasonListItemParser::getYear
     * @vcr SeasonList.yaml
     */
    public function it_gets_the_year(): void
    {
        self::assertEquals(2019, $this->parser->getYear());
    }

    /**
     * @test
     * @covers \Jikan\Parser\SeasonList\SeasonListItemParser::getSeasons
     * @vcr SeasonList.yaml
     */
    public function it_gets_the_seasons(): void
    {
        self::assertContains('Winter', $this->parser->getSeasons());
    }
}
