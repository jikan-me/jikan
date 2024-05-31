<?php

namespace JikanTest\Parser\News;

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
    private $data;

    public function setUp(): void
    {
        parent::setUp();

        $this->data = (new MalClient())
            ->getNewsSearch(
                new NewsSearchRequest('bleach')
            );
    }

    #[Test]
    public function it_gets_results()
    {
        self::assertEquals(20, count($this->data->getResults()));
    }

    #[Test]
    public function it_gets_result_item()
    {
        $entry = $this->data->getResults()[0];

        self::assertEquals(69774465, $entry->getMalId());
        self::assertEquals("https://myanimelist.net/news/69774465", $entry->getUrl());
        self::assertInstanceOf(\DateTimeImmutable::class, $entry->getDate());
        self::assertEquals("tingy", $entry->getAuthorUsername());
        self::assertEquals("https://myanimelist.net/profile/tingy", $entry->getAuthorUrl());
        self::assertEquals("https://cdn.myanimelist.net/s/common/uploaded_files/1693444935-87a2c2eb1324fea36029498dd62757c9.jpeg?s=5f5a95c52f4fee6ed352eba9ab0dc7f9", $entry->getImages()->getJpg()->getImageUrl());
        self::assertEquals(2, $entry->getComments());
        self::assertStringContainsString("Kotaro Takata, the talented artist behind the captivating manga Zom 100", $entry->getExcerpt());
        self::assertCount(3, $entry->getTags());
        self::assertEquals("Anime Expo", (string) $entry->getTags()[0]);
    }
}
