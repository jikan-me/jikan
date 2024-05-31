<?php

namespace JikanTest\Parser\News;

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
    private $data;

    public function setUp(): void
    {
        parent::setUp();

        $this->data = (new MalClient())
            ->getNewsTags(
                new NewsTagsRequest()
            );
    }

    #[Test]
    public function it_gets_results()
    {
        self::assertEquals(131, count($this->data));
    }

    #[Test]
    public function it_gets_result_item()
    {
        $entry = $this->data[0];

        self::assertEquals("New Anime", $entry->getName());
        self::assertEquals("new_anime", $entry->getMalId());
        self::assertEquals("https://myanimelist.net/news/tag/new_anime", $entry->getUrl());
        self::assertEquals("Anime", $entry->getType());
        self::assertEquals("Announcements of new anime works.", $entry->getDescription());
    }
}
