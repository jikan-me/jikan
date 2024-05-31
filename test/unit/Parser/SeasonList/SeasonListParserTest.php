<?php

namespace JikanTest\Parser\SeasonList;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\SeasonList\SeasonListItem;
use Jikan\Parser\SeasonList\SeasonListParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

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
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new SeasonListParser($crawler);
    }

    #[Test]
    public function it_contains_season_items(): void
    {
        self::assertContainsOnlyInstancesOf(
            SeasonListItem::class,
            $this->parser->getModel()->getResults()
        );
    }
}
