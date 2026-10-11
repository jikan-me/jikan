<?php

namespace JikanTest\Parser\Search;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Search\AnimeSearch;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class AnimeSearchParserTest
 *
 * Direct parser-path coverage for Jikan\Parser\Search\AnimeSearchParser — the
 * parser behind both getAnimeSearch() and getAnimeSearchAlt(). Driving the
 * endpoint parser (not just MalClient) is what the endpoint manifest counts as
 * coverage for those methods.
 */
class AnimeSearchParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Search\AnimeSearchParser
     */
    private $parser;

    /**
     * @var AnimeSearch
     */
    private $search;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Search\AnimeSearchRequest('Fate');
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new \Jikan\Parser\Search\AnimeSearchParser($crawler);
        $this->search = $this->parser->getModel();
    }

    #[Test]
    public function it_parses_the_full_result_page(): void
    {
        self::assertCount(50, $this->search->getResults());
    }

    #[Test]
    public function it_parses_the_first_result(): void
    {
        $result = $this->search->getResults()[0];
        self::assertSame(22297, $result->getMalId());
        self::assertSame('Fate/stay night: Unlimited Blade Works', $result->getTitle());
        self::assertSame('TV', $result->getType());
        self::assertSame(12, $result->getEpisodes());
        self::assertEquals(8.18, $result->getScore());
        self::assertSame(1192651, $result->getMembers());
        self::assertSame('R', $result->getRated());
    }

    #[Test]
    public function it_keeps_the_result_artwork_url(): void
    {
        $result = $this->search->getResults()[0];
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/images/anime/.*~',
            (string) $result->getImages()->getJpg()->getImageUrl()
        );
    }

    #[Test]
    public function it_reports_search_pagination(): void
    {
        self::assertTrue($this->parser->getHasNextPage());
        self::assertSame(2, $this->parser->getLastPage());
    }
}