<?php

namespace JikanTest\Parser\Article;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Article\ArticleTagsParser;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
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

    #[Test]
    public function it_builds_the_same_model_when_parsed_twice(): void
    {
        self::assertEquals($this->parser->getModel(), $this->parser->getModel());
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->parser->getModel());
    }
}
