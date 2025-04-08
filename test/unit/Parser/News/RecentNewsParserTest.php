<?php

namespace JikanTest\Parser\News;

use Jikan\Http\HttpClientWrapper;
use Jikan\MyAnimeList\MalClient;
use Jikan\Parser\News\NewsListParser;
use Jikan\Request\News\RecentNewsRequest;
use JikanTest\TestCase;
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

        self::assertEquals(72590516, $entry->getMalId());
        self::assertEquals("https://myanimelist.net/news/72590516", $entry->getUrl());
        self::assertInstanceOf(\DateTimeImmutable::class, $entry->getDate());
        self::assertEquals("DatRandomDude", $entry->getAuthorUsername());
        self::assertEquals("https://myanimelist.net/profile/DatRandomDude", $entry->getAuthorUrl());
        self::assertEquals("https://cdn.myanimelist.net/s/common/uploaded_files/1744151968-b76d21ecfe8b663a399f54a684f41e37.png?s=e3d8fd7416ebf775f8680dea57bd06d8", $entry->getImages()->getJpg()->getImageUrl());
        self::assertEquals(0, $entry->getComments());
        self::assertStringContainsString("An official website opened for a television anime adaptation", $entry->getExcerpt());
        self::assertCount(2, $entry->getTags());
        self::assertEquals("Adapts Manga", (string) $entry->getTags()[0]);
    }
}
