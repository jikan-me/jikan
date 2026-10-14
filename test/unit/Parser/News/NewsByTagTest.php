<?php

namespace JikanTest\Parser\News;

use Jikan\Http\HttpClientWrapper;
use Jikan\MyAnimeList\MalClient;
use Jikan\Request\News\NewsByTagRequest;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class NewsByTagTest
 */
class NewsByTagTest extends TestCase
{
    /**
     * @var RecentNewsParserTest
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\News\NewsByTagRequest('fall_2024');
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

        self::assertEquals(72151278, $entry->getMalId());
        $this->assertIsString($entry->getUrl());
        $this->assertNotFalse(filter_var($entry->getUrl(), FILTER_VALIDATE_URL));
        $this->assertStringStartsWith('/news/', parse_url($entry->getUrl())['path']);

        self::assertInstanceOf(\DateTimeImmutable::class, $entry->getDate());
        self::assertEquals("Vindstot", $entry->getAuthorUsername());

        $this->assertIsString($entry->getAuthorUrl());
        $this->assertNotFalse(filter_var($entry->getAuthorUrl(), FILTER_VALIDATE_URL));
        $this->assertStringStartsWith('/profile/', parse_url($entry->getAuthorUrl())['path']);

        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/.*~',
            $entry->getImages()->getJpg()->getImageUrl()
        );
        self::assertEquals(0, $entry->getComments());
        self::assertStringContainsString("The Rurouni Kenshin: Meiji Kenkaku Romantan - Kyoto Douran", $entry->getExcerpt());
        self::assertCount(5, $entry->getTags());
        self::assertEquals("OP ED", (string) $entry->getTags()[1]);
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
