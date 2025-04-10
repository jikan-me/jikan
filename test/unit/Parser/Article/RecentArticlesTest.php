<?php

namespace JikanTest\Parser\Article;

use Jikan\Http\HttpClientWrapper;
use Jikan\MyAnimeList\MalClient;
use Jikan\Parser\Article\ArticleListParser;
use Jikan\Request\Article\ArticlesByTagRequest;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class RecentArticlesTest
 */
class RecentArticlesTest extends TestCase
{
    /**
     * @var ArticleListParser
     */
    private ArticleListParser $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Article\RecentArticleRequest(1);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Article\ArticleListParser($crawler);
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

        self::assertEquals(2398, $entry->getMalId());
        self::assertEquals("https://myanimelist.net/featured/2398/Behind_the_Masks__Unveiling_the_mystery_gothic_anime_Ave_Mujica", $entry->getUrl());
        self::assertEquals("MAL_editing_team", $entry->getAuthorUsername());
        self::assertEquals("https://myanimelist.net/profile/MAL_editing_team", $entry->getAuthorUrl());
        self::assertEquals("https://cdn.myanimelist.net/s/common/uploaded_files/1734918805-7d43d1c96aa13b0f26d4da1523d973d2.jpeg", $entry->getImages()->getJpg()->getImageUrl());
        self::assertEquals(27140, $entry->getViews());
        self::assertStringContainsString("Ave Mujica - The Die is Cast - Exclusive interview with Director Kodai Kakimoto and Band Producer Hiroki Matsumoto.", $entry->getExcerpt());
        self::assertCount(0, $entry->getTags());
    }
}
