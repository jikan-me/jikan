<?php

namespace JikanTest\Parser\News;

use Jikan\MyAnimeList\MalClient;
use Jikan\Request\News\NewsRequest;
use Jikan\Request\News\NewsTagsRequest;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;
use function PHPUnit\Framework\assertEquals;

/**
 * Class NewsTest
 */
class NewsTest extends TestCase
{
    private $data;

    public function setUp(): void
    {
        parent::setUp();

        $this->data = (new MalClient())
            ->getNews(
                new NewsRequest(70748275)
            );
    }

    #[Test]
    public function it_gets_title()
    {
        self::assertEquals("'Re:Zero kara Hajimeru Isekai Seikatsu' Season 3 Reveals New Staff, Cast, First Promo for Fall 2024", $this->data->getTitle());
    }

    #[Test]
    public function it_gets_author()
    {
        self::assertEquals("DatRandomDude", $this->data->getAuthorUsername());
    }

    #[Test]
    public function it_gets_content()
    {
        self::assertStringContainsString("White Fox produced the first anime season in Spring 2016.", $this->data->getContent());
    }

    #[Test]
    public function it_gets_image()
    {
        self::assertEquals("https://cdn.myanimelist.net/s/common/uploaded_files/1711247040-f0e4443ae58814bfb46845fe448c5850.png", $this->data->getImages()->getJpg()->getImageUrl());
    }

    #[Test]
    public function it_gets_comments()
    {
        self::assertEquals(12, $this->data->getComments());
    }

    #[Test]
    public function it_gets_related_entries()
    {
        self::assertCount(1, $this->data->getRelatedEntries());
        self::assertEquals("Re:Zero kara Hajimeru Isekai Seikatsu 3rd Season", $this->data->getRelatedEntries()["Anime"][0]->getTitle());
        self::assertEquals("anime", $this->data->getRelatedEntries()["Anime"][0]->getType());
    }

    #[Test]
    public function it_gets_related_news()
    {
        self::assertEquals("'Re:Zero kara Hajimeru Isekai Seikatsu' Gets Third Anime Season", (string) $this->data->getRelatedNews()[0]);
    }

    #[Test]
    public function it_gets_tags()
    {
        self::assertCount(4, $this->data->getTags());
        self::assertEquals("More Info", $this->data->getTags()[2]->getName());
    }
}
