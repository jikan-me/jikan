<?php

namespace JikanTest\Parser\Search;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Search\PersonSearchParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class PersonSearchParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Search\PersonSearchParser
 * (getPersonSearch), driving a search-query page through the endpoint parser.
 */
class PersonSearchParserTest extends TestCase
{
    /**
     * @var PersonSearchParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Search\PersonSearchRequest('Yamaguchi');
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new PersonSearchParser($crawler);
    }

    #[Test]
    public function it_parses_the_full_result_page(): void
    {
        self::assertCount(50, $this->parser->getResults());
    }

    #[Test]
    public function it_parses_the_first_result(): void
    {
        $result = $this->parser->getResults()[0];
        self::assertSame(67, $result->getMalId());
        self::assertSame('Yamaguchi, Kappei', $result->getName());
        self::assertContains('Mitsuo Yamaguchi', $result->getAlternativeNames());
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/images/voiceactors/.*~',
            (string) $result->getImages()->getJpg()->getImageUrl()
        );
    }

    #[Test]
    public function it_reports_search_pagination(): void
    {
        self::assertTrue($this->parser->getHasNextPage());
        self::assertSame(4, $this->parser->getLastPage());
    }
}