<?php

namespace JikanTest\Parser\Top;

use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class TopPeopleParserPageTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Top\TopPeopleParser
 * (getTopPeople), driving the whole ranking page through the endpoint parser.
 */
class TopPeopleParserPageTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Top\TopPeopleParser
     */
    private $parser;

    /**
     * @var \Jikan\Model\Top\TopPeople
     */
    private $top;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Top\TopPeopleRequest();
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new \Jikan\Parser\Top\TopPeopleParser($crawler);
        $this->top = $this->parser->getModel();
    }

    #[Test]
    public function it_parses_the_full_ranking_page(): void
    {
        self::assertCount(50, $this->top->getResults());
    }

    #[Test]
    public function it_parses_the_top_person(): void
    {
        $result = $this->top->getResults()[0];
        self::assertSame(1, $result->getRank());
        self::assertSame(118, $result->getMalId());
        self::assertSame('Kamiya, Hiroshi', $result->getTitle());
        self::assertNull($result->getNameKanji());
        self::assertSame(108677, $result->getFavorites());
        self::assertSame('1975-01-28', $result->getBirthday()->format('Y-m-d'));
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/images/voiceactors/.*~',
            (string) $result->getImages()->getJpg()->getImageUrl()
        );
    }

    #[Test]
    public function it_parses_the_last_row_of_the_page(): void
    {
        $results = $this->top->getResults();
        $last = $results[count($results) - 1];
        self::assertSame(50, $last->getRank());
        self::assertSame(11184, $last->getMalId());
        self::assertSame('Touyama, Nao', $last->getTitle());
        self::assertSame(16300, $last->getFavorites());
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
