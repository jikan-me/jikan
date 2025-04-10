<?php

namespace JikanTest\Parser\Article;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Article\ArticleListParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class ArticlesByTagTest
 */
class ArticlesByTagTest extends TestCase
{
    /**
     * @var ArticleListParser
     */
    private ArticleListParser $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Article\ArticlesByTagRequest('interview');
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

        self::assertEquals(1956, $entry->getMalId());
        self::assertEquals("https://myanimelist.net/featured/1956/Interview_With_Vic_Mignogna_Voice_Actor_of_Edward_Elric___Broly", $entry->getUrl());
        self::assertEquals("Jankenpopp", $entry->getAuthorUsername());
        self::assertEquals("https://myanimelist.net/profile/Jankenpopp", $entry->getAuthorUrl());
        self::assertEquals("https://cdn.myanimelist.net/s/common/uploaded_files/1476869204-81bd716e087a287a3f4eb1a9b7d5d359.jpeg", $entry->getImages()->getJpg()->getImageUrl());
        self::assertEquals(53896, $entry->getViews());
        self::assertStringContainsString("Edward Elric. Zero Kiryuu. Broly. Vic Mignogna has an impressive voice", $entry->getExcerpt());
        self::assertCount(1, $entry->getTags());
    }
}
