<?php

namespace JikanTest\Parser\Search;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Search\MangaSearchParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class MangaSearchParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Search\MangaSearchParser
 * (getMangaSearch), driving a search-query page through the endpoint parser.
 */
class MangaSearchParserTest extends TestCase
{
    /**
     * @var MangaSearchParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Search\MangaSearchRequest('Berserk');
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new MangaSearchParser($crawler);
    }

    #[Test]
    public function it_parses_the_full_result_page(): void
    {
        self::assertCount(16, $this->parser->getResults());
    }

    #[Test]
    public function it_parses_the_first_result(): void
    {
        $result = $this->parser->getResults()[0];
        self::assertSame(2, $result->getMalId());
        self::assertSame('Berserk', $result->getTitle());
        self::assertSame('Manga', $result->getType());
        self::assertEquals(9.46, $result->getScore());
        self::assertSame(811730, $result->getMembers());
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/images/manga/.*~',
            (string) $result->getImages()->getJpg()->getImageUrl()
        );
    }

    #[Test]
    public function it_reports_search_pagination(): void
    {
        self::assertFalse($this->parser->getHasNextPage());
        self::assertSame(1, $this->parser->getLastPage());
    }
}