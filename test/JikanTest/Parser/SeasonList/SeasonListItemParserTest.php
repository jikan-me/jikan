<?php
/**
 * Created by PhpStorm.
 * User: miraris
 * Date: 10.7.18
 * Time: 19:42
 */

namespace Jikan\Parser\SeasonList;

use Jikan\Parser\SeasonList\SeasonListItemParser;
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
            $crawler->filterXPath('//table[contains(@class, "anime-seasonal-byseason")]/tr')->eq(1)
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\SeasonList\SeasonListItemParser::getYear
     * @vcr SeasonListItemParser.yaml
     */
    public function it_gets_the_year(): void
    {
        self::assertEquals(2018, $this->parser->getYear());
    }

    /**
     * @test
     * @covers \Jikan\Parser\SeasonList\SeasonListItemParser::getSeasons
     * @vcr SeasonListItemParser.yaml
     */
    public function it_gets_the_seasons(): void
    {
        self::assertContains('Winter', $this->parser->getSeasons());
    }
}
