<?php

namespace JikanTest\Parser\Top;

use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class TopMangaParserPageTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Top\TopMangaParser (getTopManga),
 * driving the whole ranking page through the endpoint parser.
 */
class TopMangaParserPageTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Top\TopMangaParser
     */
    private $parser;

    /**
     * @var \Jikan\Model\Top\TopManga
     */
    private $top;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Top\TopMangaRequest();
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new \Jikan\Parser\Top\TopMangaParser($crawler);
        $this->top = $this->parser->getModel();
    }

    #[Test]
    public function it_parses_the_full_ranking_page(): void
    {
        self::assertCount(50, $this->top->getResults());
    }

    #[Test]
    public function it_parses_the_top_manga(): void
    {
        $result = $this->top->getResults()[0];
        self::assertSame(1, $result->getRank());
        self::assertSame(2, $result->getMalId());
        self::assertSame('Berserk', $result->getTitle());
        self::assertSame('Manga', $result->getType());
        self::assertNull($result->getVolumes());
        self::assertEquals(9.46, $result->getScore());
        self::assertSame('Aug 1989', $result->getStartDate());
        self::assertNull($result->getEndDate());
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/images/manga/.*~',
            (string) $result->getImages()->getJpg()->getImageUrl()
        );
    }

    #[Test]
    public function it_parses_the_last_row_of_the_page(): void
    {
        $results = $this->top->getResults();
        $last = $results[count($results) - 1];
        self::assertSame(50, $last->getRank());
        self::assertSame(129621, $last->getMalId());
        self::assertSame('Medalist', $last->getTitle());
        self::assertSame(15, $last->getVolumes());
    }

    #[Test]
    public function it_reports_ranking_pagination(): void
    {
        self::assertTrue($this->parser->getHasNextPage());
        self::assertSame(2, $this->parser->getLastPage());
        self::assertSame(2, $this->top->getLastVisiblePage());
    }
}