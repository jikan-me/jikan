<?php

namespace Parser\SeasonList;

use Jikan\Model\SeasonList\SeasonListItem;
use Jikan\Parser\SeasonList\SeasonListParser;
use JikanTest\Parser\SeasonList\Client;
use TestCase;

/**
 * Class SeasonListParserTest
 */
class SeasonListParserTest extends TestCase
{
    /**
     * @var SeasonListParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\SeasonList\SeasonListRequest();
        $client = new Client($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new SeasonListParser($crawler);
    }

    /**
     * @test
     * @covers \Jikan\Parser\SeasonList\SeasonListParser::getModel
     */
    public function it_contains_season_items(): void
    {
        self::assertContainsOnlyInstancesOf(
            SeasonListItem::class,
            $this->parser->getModel()->getResults()
        );
    }
}
