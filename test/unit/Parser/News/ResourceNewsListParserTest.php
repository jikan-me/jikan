<?php

namespace JikanTest\Parser\News;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\News\ResourceNewsListParser;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class ResourceNewsListParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\News\ResourceNewsListParser
 * (getNewsList) against the current main news index (div.news-unit layout;
 * ad placements injected into the list are filtered out by the parser).
 */
class ResourceNewsListParserTest extends TestCase
{
    /**
     * @var ResourceNewsListParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/news');

        $this->parser = new ResourceNewsListParser($crawler);
    }

    #[Test]
    public function it_parses_the_news_index_items(): void
    {
        self::assertCount(20, $this->parser->getResults());
    }

    #[Test]
    public function it_parses_the_first_news_item(): void
    {
        $item = $this->parser->getResults()[0];
        self::assertSame(74811025, $item->getMalId());
        self::assertSame(
            "'Noa-senpai wa Tomodachi' Reveals Main Cast, 2027 Debut",
            $item->getTitle()
        );
        self::assertSame('https://myanimelist.net/news/74811025', $item->getUrl());
        self::assertSame('Syureria', $item->getAuthorUsername());
        self::assertSame(3, $item->getComments());
        self::assertSame(
            'https://myanimelist.net/forum/?topicid=2282401',
            $item->getForumUrl()
        );
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/.*~',
            (string) $item->getImages()->getJpg()->getImageUrl()
        );
    }

    #[Test]
    public function it_parses_the_excerpt(): void
    {
        self::assertStringContainsString(
            'The official website for the television anime adaptation',
            $this->parser->getResults()[0]->getExcerpt()
        );
    }

    #[Test]
    public function it_reports_pagination(): void
    {
        self::assertTrue($this->parser->getHasNextPage());
    }

    #[Test]
    public function it_builds_the_same_model_when_parsed_twice(): void
    {
        self::assertEquals($this->parser->getModel(), $this->parser->getModel());
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->parser->getModel());
    }
}
