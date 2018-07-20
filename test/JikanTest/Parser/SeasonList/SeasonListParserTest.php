<?php

namespace JikanTest\Parser\SeasonList;

use Goutte\Client;
use Jikan\Model\SeasonList\SeasonListItem;
use Jikan\Parser\SeasonList\SeasonListParser;
use PHPUnit\Framework\TestCase;

/**
 * Class SeasonListParserTest
 */
class SeasonListParserTest extends TestCase
{
    /**
     * @var SeasonListParser
     */
    private $parser;

    public function setUp()
    {
        $request = new \Jikan\Request\SeasonList\SeasonListRequest();
        $client = new Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new SeasonListParser($crawler);
    }

    /**
     * @test
     * @covers \Jikan\Parser\SeasonList\SeasonListParser::getModel
     * @vcr SeasonList.yaml
     */
    public function it_contains_season_items(): void
    {
        self::assertContainsOnlyInstancesOf(
            SeasonListItem::class,
            $this->parser->getModel()
        );
    }
}
