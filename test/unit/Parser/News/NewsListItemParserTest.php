<?php

namespace JikanTest\Parser\News;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\News\NewsListItemParser;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class NewsListItemParserTest
 *
 * NewsListItemParser parses the main news index items (div.news-unit with the
 * exact class — ad placements injected under the same class are skipped).
 * The previous test targeted the manga-news-tab js-scrollfix/.clearfix layout,
 * which was the wrong page for this parser; it now drives the /news index.
 */
class NewsListItemParserTest extends TestCase
{
    /**
     * @var NewsListItemParser
     */
    private NewsListItemParser $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/news');

        $unit = $crawler
            ->filterXPath('//div[contains(concat(" ", normalize-space(@class), " "), " news-unit ")]')
            ->reduce(
                static fn (Crawler $c): bool => $c->filterXPath('.//p[contains(@class, "title")]/a')->count() > 0
            )
            ->first();

        $this->parser = new NewsListItemParser($unit);
    }

    #[Test]
    public function it_gets_the_title(): void
    {
        self::assertSame("'Noa-senpai wa Tomodachi' Reveals Main Cast, 2027 Debut", $this->parser->getTitle());
    }

    #[Test]
    public function it_gets_the_url(): void
    {
        self::assertSame('https://myanimelist.net/news/74811025', $this->parser->getUrl());
    }

    #[Test]
    public function it_gets_the_image(): void
    {
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/.*~',
            $this->parser->getImageUrl()
        );
    }

    #[Test]
    public function it_gets_the_date(): void
    {
        // The index serves relative dates ("11 hours ago"); assert plausibility.
        $date = $this->parser->getDate();
        self::assertNotNull($date);
        self::assertGreaterThan(
            (new \DateTimeImmutable('-30 days', new \DateTimeZone('UTC')))->format('Y-m-d'),
            $date->format('Y-m-d')
        );
    }

    #[Test]
    public function it_gets_the_author(): void
    {
        self::assertSame('Syureria', (string) $this->parser->getAuthor());
    }

    #[Test]
    public function it_gets_the_discussion_link(): void
    {
        self::assertSame('https://myanimelist.net/forum/?topicid=2282401', $this->parser->getDiscussionLink());
    }

    #[Test]
    public function it_gets_the_comments(): void
    {
        self::assertSame(3, $this->parser->getComments());
    }

    #[Test]
    public function it_gets_the_introduction(): void
    {
        self::assertStringContainsString(
            'The official website for the television anime adaptation',
            $this->parser->getExcerpt()
        );
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
