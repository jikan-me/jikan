<?php

namespace JikanTest\Parser\News;

use Jikan\Http\HttpClientWrapper;
use Jikan\MyAnimeList\MalClient;
use Jikan\Request\News\NewsByTagRequest;
use Jikan\Request\Search\NewsSearchRequest;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class NewsSearchTest
 */
class NewsSearchTest extends TestCase
{
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Search\NewsSearchRequest('bleach');
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

        self::assertEquals(72179758, $entry->getMalId());
        self::assertEquals("https://myanimelist.net/news/72179758", $entry->getUrl());
        self::assertInstanceOf(\DateTimeImmutable::class, $entry->getDate());
        self::assertEquals("Vindstot", $entry->getAuthorUsername());
        self::assertEquals("https://myanimelist.net/profile/Vindstot", $entry->getAuthorUrl());
        self::assertEquals("https://cdn.myanimelist.net/s/common/uploaded_files/1735411918-1602672abbdcd42634b42971af484964.jpeg?s=9c9cebbaf9606225d4b424fed5091338", $entry->getImages()->getJpg()->getImageUrl());
        self::assertEquals(16, $entry->getComments());
        self::assertStringContainsString("The 14th and final episode of the Bleach: Sennen Kessen-hen", $entry->getExcerpt());
        self::assertCount(2, $entry->getTags());
        self::assertEquals("Preview", (string) $entry->getTags()[0]);
    }
}
