<?php

namespace JikanTest\Parser\Article;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Article\ArticleListParser;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
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

        $this->assertIsString($entry->getUrl());
        $this->assertNotFalse(filter_var($entry->getUrl(), FILTER_VALIDATE_URL));
        $this->assertStringStartsWith('/featured/', parse_url($entry->getUrl())['path']);

        self::assertEquals("MAL_editing_team", $entry->getAuthorUsername());

        $this->assertIsString($entry->getAuthorUrl());
        $this->assertNotFalse(filter_var($entry->getAuthorUrl(), FILTER_VALIDATE_URL));
        $this->assertStringStartsWith('/profile/', parse_url($entry->getAuthorUrl())['path']);

        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/.*~',
            $entry->getImages()->getJpg()->getImageUrl()
        );
        self::assertIsNumeric($entry->getViews());
        self::assertStringContainsString("Breaking down the hottest panels at Aniplex Online Fest 2021, featuring Puella Magi Madoka Magica", $entry->getExcerpt());
        self::assertCount(0, $entry->getTags());
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
