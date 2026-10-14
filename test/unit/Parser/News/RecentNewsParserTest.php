<?php

namespace JikanTest\Parser\News;

use Jikan\Http\HttpClientWrapper;
use Jikan\MyAnimeList\MalClient;
use Jikan\Parser\News\NewsListParser;
use Jikan\Request\News\RecentNewsRequest;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class AnimeGenreParserTest
 */
class RecentNewsParserTest extends TestCase
{
    /**
     * @var RecentNewsParserTest
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\News\RecentNewsRequest(1);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\News\NewsListParser($crawler);
    }

    #[Test]
    public function it_gets_results()
    {
        self::assertEquals(20, count($this->parser->getResults()));
    }

    #[Test]
    public function it_gets_result_item()
    {
        $entry = $this->parser->getResults()[0];

        self::assertEquals(73734818, $entry->getMalId());

        $this->assertIsString($entry->getUrl());
        $this->assertNotFalse(filter_var($entry->getUrl(), FILTER_VALIDATE_URL));
        $this->assertStringStartsWith('/news/', parse_url($entry->getUrl())['path']);

        self::assertInstanceOf(\DateTimeImmutable::class, $entry->getDate());
        self::assertEquals("nirererin", $entry->getAuthorUsername());

        $this->assertIsString($entry->getAuthorUrl());
        $this->assertNotFalse(filter_var($entry->getAuthorUrl(), FILTER_VALIDATE_URL));
        $this->assertStringStartsWith('/profile/', parse_url($entry->getAuthorUrl())['path']);

        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/.*~',
            $entry->getImages()->getJpg()->getImageUrl()
        );
        self::assertEquals(17, $entry->getComments());
        self::assertStringContainsString("Production company Kadokawa opened an official website for the television anime adaptation of Sanji Jiksong and", $entry->getExcerpt());
        self::assertCount(4, $entry->getTags());
        self::assertEquals("Adapts Manga", (string) $entry->getTags()[0]);
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
