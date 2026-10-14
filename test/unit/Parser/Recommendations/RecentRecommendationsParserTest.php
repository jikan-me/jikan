<?php

namespace JikanTest\Parser\Recommendations;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Recommendations\RecentRecommendationsParser;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class RecentRecommendationsParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Recommendations\RecentRecommendationsParser
 * (getRecentRecommendations) against the recent anime recommendations listing.
 */
class RecentRecommendationsParserTest extends TestCase
{
    /**
     * @var RecentRecommendationsParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Recommendations\RecentRecommendationsRequest();
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new RecentRecommendationsParser($crawler);
    }

    #[Test]
    public function it_parses_the_recent_recommendations(): void
    {
        $items = $this->parser->getRecentRecommendations();
        self::assertCount(100, $items);
    }

    #[Test]
    public function it_parses_the_first_recommendation(): void
    {
        $item = $this->parser->getRecentRecommendations()[0];
        $entry = $item->getEntry();
        self::assertSame('4081-10408', $item->getMalId());
        self::assertSame('Natsume Yuujinchou', $entry[0]->getTitle());
        self::assertSame(4081, $entry[0]->getMalId());
        self::assertSame('Hotarubi no Mori e', $entry[1]->getTitle());
        self::assertSame('white_and_fluffy', $item->getUser()->getUsername());
        self::assertStringContainsString('forest of fireflies', $item->getContent());
    }

    #[Test]
    public function it_reports_pagination(): void
    {
        self::assertSame(20, $this->parser->getLastPage());
    }

    #[Test]
    public function it_builds_the_same_model_when_parsed_twice(): void
    {
        // Relative dates ("5 minutes ago") resolve against the clock at parse time, so two
        // parses differ by microseconds; allow a small delta instead of exact equality.
        self::assertEqualsWithDelta($this->parser->getModel(), $this->parser->getModel(), 5.0);
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->parser->getModel(), true);
    }
}
