<?php

namespace JikanTest\Parser\Article;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Article\ArticleTagsParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class ArticleTagsTest
 */
class ArticleTagsTest extends TestCase
{
    /**
     * @var ArticleTagsParser
     */
    private ArticleTagsParser $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Article\ArticleTagsRequest();
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Article\ArticleTagsParser($crawler);
    }

    #[Test]
    public function it_gets_result_item()
    {
        $entry = $this->parser->getModel()[0];

        self::assertEquals("interview", $entry->getMalId());
        self::assertEquals("https://myanimelist.net/featured/tag/interview", $entry->getUrl());
    }
}
