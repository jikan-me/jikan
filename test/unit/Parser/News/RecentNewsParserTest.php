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
    private $data;

    public function setUp(): void
    {
        parent::setUp();

        $this->data = (new MalClient())
            ->getRecentNews(
                new RecentNewsRequest()
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

        self::assertEquals(71147094, $entry->getMalId());
        self::assertEquals("https://myanimelist.net/news/71147094", $entry->getUrl());
        self::assertInstanceOf(\DateTimeImmutable::class, $entry->getDate());
        self::assertEquals("DatRandomDude", $entry->getAuthorUsername());
        self::assertEquals("https://myanimelist.net/profile/DatRandomDude", $entry->getAuthorUrl());
        self::assertEquals("https://cdn.myanimelist.net/s/common/uploaded_files/1717110506-c2df0122ab28537fbc74e36192e28523.jpeg?s=ba0c8cc3f258481898c12d5e7230fe30", $entry->getImages()->getJpg()->getImageUrl());
        self::assertEquals(5, $entry->getComments());
        self::assertStringContainsString("The official website for the Boku no Hero Academia", $entry->getExcerpt());
        self::assertCount(1, $entry->getTags());
        self::assertEquals("More Info", (string) $entry->getTags()[0]);
    }
}
