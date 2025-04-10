<?php

namespace JikanTest\Parser\News;

use Jikan\Http\HttpClientWrapper;
use Jikan\MyAnimeList\MalClient;
use Jikan\Request\News\NewsByTagRequest;
use Jikan\Request\News\NewsTagsRequest;
use Jikan\Request\Search\NewsSearchRequest;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class NewsTagsTest
 */
class NewsTagsTest extends TestCase
{
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\News\NewsTagsRequest();
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\News\NewsTagsParser($crawler);
    }

    #[Test]
    public function it_gets_results()
    {
        self::assertEquals(135, count($this->parser->getModel()));
    }

    #[Test]
    public function it_gets_result_item()
    {
        $entry = $this->parser->getModel()[0];

        self::assertEquals("New Anime", $entry->getName());
        self::assertEquals("new_anime", $entry->getMalId());
        self::assertEquals("https://myanimelist.net/news/tag/new_anime", $entry->getUrl());
        self::assertEquals("Anime", $entry->getType());
        self::assertEquals("Announcements of new anime works.", $entry->getDescription());
    }
}
