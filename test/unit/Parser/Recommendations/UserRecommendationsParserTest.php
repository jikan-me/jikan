<?php

namespace JikanTest\Parser\Recommendations;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Recommendations\UserRecommendationsParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class UserRecommendationsParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Recommendations\UserRecommendationsParser
 * (getUserRecommendations) against a user's recommendation history page.
 */
class UserRecommendationsParserTest extends TestCase
{
    /**
     * @var UserRecommendationsParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\User\UserRecommendationsRequest('DatRandomDude');
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new UserRecommendationsParser($crawler);
    }

    #[Test]
    public function it_parses_the_users_recommendations(): void
    {
        $items = $this->parser->getUserRecommendations();
        self::assertCount(3, $items);
    }

    #[Test]
    public function it_parses_the_first_recommendation(): void
    {
        $item = $this->parser->getUserRecommendations()[0];
        $entry = $item->getEntry();
        self::assertSame('17909-19703', $item->getMalId());
        self::assertSame('Uchouten Kazoku', $entry[0]->getTitle());
        self::assertSame('DatRandomDude', $item->getUser()->getUsername());
        self::assertStringContainsString('fantasy adventures', $item->getContent());
    }

    #[Test]
    public function it_reports_pagination(): void
    {
        self::assertSame(1, $this->parser->getLastPage());
    }
}