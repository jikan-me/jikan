<?php

namespace JikanTest\Parser\Top;

use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class TopAnimeParserPageTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Top\TopAnimeParser (getTopAnime).
 * The existing TopAnimeParserTest drives a single ranking row through
 * TopListItemParser; this test drives the whole page through the endpoint
 * parser so the endpoint manifest counts it as covered.
 */
class TopAnimeParserPageTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Top\TopAnimeParser
     */
    private $parser;

    /**
     * @var \Jikan\Model\Top\TopAnime
     */
    private $top;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Top\TopAnimeRequest();
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new \Jikan\Parser\Top\TopAnimeParser($crawler);
        $this->top = $this->parser->getModel();
    }

    #[Test]
    public function it_parses_the_full_ranking_page(): void
    {
        self::assertCount(50, $this->top->getResults());
    }

    #[Test]
    public function it_parses_the_top_anime(): void
    {
        $result = $this->top->getResults()[0];
        self::assertSame(1, $result->getRank());
        self::assertSame(52991, $result->getMalId());
        self::assertSame('Sousou no Frieren', $result->getTitle());
        self::assertSame('TV', $result->getType());
        self::assertSame(28, $result->getEpisodes());
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/images/anime/.*~',
            (string) $result->getImages()->getJpg()->getImageUrl()
        );
    }

    #[Test]
    public function it_parses_the_last_row_of_the_page(): void
    {
        $results = $this->top->getResults();
        $last = $results[count($results) - 1];
        self::assertSame(50, $last->getRank());
        self::assertSame(17074, $last->getMalId());
        self::assertSame('Monogatari Series: Second Season', $last->getTitle());
    }

    #[Test]
    public function it_reports_ranking_pagination(): void
    {
        self::assertTrue($this->parser->getHasNextPage());
        self::assertSame(2, $this->parser->getLastPage());
        self::assertSame(2, $this->top->getLastVisiblePage());
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
