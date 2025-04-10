<?php

namespace JikanTest\Parser\News;

use Jikan\Http\HttpClientWrapper;
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
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\News\NewsRequest(70748275);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\News\NewsParser($crawler);
    }

    #[Test]
    public function it_gets_title()
    {
        self::assertEquals("'Re:Zero kara Hajimeru Isekai Seikatsu' Season 3 Reveals New Staff, Cast, First Promo for Fall 2024", $this->parser->getTitle());
    }

    #[Test]
    public function it_gets_author()
    {
        self::assertEquals("DatRandomDude", $this->parser->getAuthor()->getName());
    }

    #[Test]
    public function it_gets_content()
    {
        self::assertStringContainsString("White Fox produced the first anime season in Spring 2016.", $this->parser->getContent());
    }

    #[Test]
    public function it_gets_image()
    {
        self::assertEquals("https://cdn.myanimelist.net/s/common/uploaded_files/1711247040-f0e4443ae58814bfb46845fe448c5850.png", $this->parser->getImageUrl());
    }

    #[Test]
    public function it_gets_comments()
    {
        self::assertEquals(12, $this->parser->getComments());
    }

    #[Test]
    public function it_gets_related_entries()
    {
        self::assertCount(1, $this->parser->getRelatedEntries());
        self::assertEquals("Re:Zero kara Hajimeru Isekai Seikatsu 3rd Season", $this->parser->getRelatedEntries()["Anime"][0]->getTitle());
        self::assertEquals("anime", $this->parser->getRelatedEntries()["Anime"][0]->getType());
    }

    #[Test]
    public function it_gets_related_news()
    {
        self::assertEquals("'Re:Zero kara Hajimeru Isekai Seikatsu' Season 3 Reveals Additional Cast, Theme Songs, 90-Minute Premiere", (string) $this->parser->getRelatedNews()[0]);
    }

    #[Test]
    public function it_gets_tags()
    {
        self::assertCount(4, $this->parser->getTags());
        self::assertEquals("More Info", $this->parser->getTags()[2]->getName());
    }
}
