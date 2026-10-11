<?php

namespace JikanTest\Parser\Article;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Article\ArticleParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class ArticleParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Article\ArticleParser (getArticle)
 * against a real featured article (featured/123). Featured pages serve the
 * view count in the article's info block; pages without one resolve to null.
 */
class ArticleParserTest extends TestCase
{
    /**
     * @var ArticleParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Article\ArticleRequest(123);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new ArticleParser($crawler);
    }

    #[Test]
    public function it_parses_the_featured_article(): void
    {
        $model = $this->parser->getModel();
        self::assertSame(123, $model->getMalId());
        self::assertSame(
            'https://myanimelist.net/featured/123/Durararax2_Ten_-_Episode_7_Recap',
            $model->getUrl()
        );
        self::assertSame('Durarara!!x2 Ten - Episode 7 Recap', $model->getTitle());
        self::assertSame('5camp', $model->getAuthorUsername());
        self::assertSame('2015-08-19', $model->getDate()->format('Y-m-d'));
    }

    #[Test]
    public function it_parses_the_view_count(): void
    {
        $views = $this->parser->getViews();
        self::assertIsInt($views);
        self::assertGreaterThan(9000, $views);
    }

    #[Test]
    public function it_parses_content_and_related_articles(): void
    {
        self::assertStringContainsString(
            'Everything is popping off in Durarara!!x2 Ten now.',
            $this->parser->getExcerpt()
        );
        self::assertGreaterThan(1000, strlen($this->parser->getContent()));
        self::assertCount(2, $this->parser->getTags());
        self::assertCount(5, $this->parser->getRelatedArticles());
    }

    #[Test]
    public function it_keeps_the_page_parsable_across_getters(): void
    {
        // getDate must not mutate the shared DOM; views stay readable after it.
        self::assertSame('2015-08-19', $this->parser->getDate()?->format('Y-m-d'));
        self::assertIsInt($this->parser->getViews());
    }
}