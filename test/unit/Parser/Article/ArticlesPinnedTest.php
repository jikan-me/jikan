<?php

namespace JikanTest\Parser\Article;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Article\PinnedArticleListParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class ArticlesPinnedTest
 */
class ArticlesPinnedTest extends TestCase
{
    /**
     * @var PinnedArticleListParser
     */
    private PinnedArticleListParser $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Article\PinnedArticlesRequest();
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Article\PinnedArticleListParser($crawler);
    }

    #[Test]
    public function it_gets_results()
    {
        self::assertEquals(4, count($this->parser->getResults()));
    }

    #[Test]
    public function it_gets_result_item()
    {
        $entry = $this->parser->getResults()[0];

        self::assertEquals(2399, $entry->getMalId());
        self::assertEquals("https://myanimelist.net/featured/2399/Which_Futuristic_High-Tech_World_Should_Be_Adapted_by_WIT_Studio", $entry->getUrl());
        self::assertEquals("MAL_editing_team", $entry->getAuthorUsername());
        self::assertEquals("https://myanimelist.net/profile/MAL_editing_team", $entry->getAuthorUrl());
        self::assertEquals("https://cdn.myanimelist.net/s/common/uploaded_files/1739950224-05e29491bfcc1729b3affbb08e6ab53e.png?s=ac1dcf66aa9d390008d61091eb5d6afe", $entry->getImages()->getJpg()->getImageUrl());
        self::assertEquals(15769, $entry->getViews());
        self::assertStringContainsString("Here are the 10 finalists from the MAL x Honeyfeed Writing Contest 2024!", $entry->getExcerpt());
        self::assertCount(0, $entry->getTags());
    }
}
