<?php

namespace JikanTest\Parser\Article;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Article\ArticleListParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class ArticleSearchTest
 */
class ArticleSearchTest extends TestCase
{
    /**
     * @var ArticleListParser
     */
    private ArticleListParser $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Search\ArticleSearchRequest('test', 1);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Article\ArticleListParser($crawler);
    }

    #[Test]
    public function it_gets_results()
    {
        self::assertEquals(19, count($this->parser->getResults()));
    }

    #[Test]
    public function it_gets_result_item()
    {
        $entry = $this->parser->getResults()[0];

        self::assertEquals(2362, $entry->getMalId());
        self::assertEquals("https://myanimelist.net/featured/2362/Special_Report_from_Aniplex_Online_Fest_2021", $entry->getUrl());
        self::assertEquals("MAL_editing_team", $entry->getAuthorUsername());
        self::assertEquals("https://myanimelist.net/profile/MAL_editing_team", $entry->getAuthorUrl());
        self::assertEquals("https://cdn.myanimelist.net/s/common/uploaded_files/1625817089-5243aa73d42dac6444e7cad5e611eda6.jpeg", $entry->getImages()->getJpg()->getImageUrl());
        self::assertEquals(47365, $entry->getViews());
        self::assertStringContainsString("Breaking down the hottest panels at Aniplex Online Fest 2021, featuring Puella Magi Madoka Magica", $entry->getExcerpt());
        self::assertCount(0, $entry->getTags());
    }
}
