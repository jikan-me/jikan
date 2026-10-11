<?php

namespace JikanTest\Parser\Top;

use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class TopCharactersParserPageTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Top\TopCharactersParser
 * (getTopCharacters), driving the whole ranking page through the endpoint
 * parser.
 */
class TopCharactersParserPageTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Top\TopCharactersParser
     */
    private $parser;

    /**
     * @var \Jikan\Model\Top\TopCharacters
     */
    private $top;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Top\TopCharactersRequest();
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new \Jikan\Parser\Top\TopCharactersParser($crawler);
        $this->top = $this->parser->getModel();
    }

    #[Test]
    public function it_parses_the_full_ranking_page(): void
    {
        self::assertCount(50, $this->top->getResults());
    }

    #[Test]
    public function it_parses_the_top_character(): void
    {
        $result = $this->top->getResults()[0];
        self::assertSame(1, $result->getRank());
        self::assertSame(417, $result->getMalId());
        self::assertSame('Lamperouge, Lelouch', $result->getTitle());
        self::assertNull($result->getNameKanji());
        self::assertSame(181470, $result->getFavorites());
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/images/characters/.*~',
            (string) $result->getImages()->getJpg()->getImageUrl()
        );
    }

    #[Test]
    public function it_parses_the_last_row_of_the_page(): void
    {
        $results = $this->top->getResults();
        $last = $results[count($results) - 1];
        self::assertSame(50, $last->getRank());
        self::assertSame(13, $last->getMalId());
        self::assertSame('Uchiha, Sasuke', $last->getTitle());
        self::assertSame(30465, $last->getFavorites());
    }

    #[Test]
    public function it_reports_ranking_pagination(): void
    {
        self::assertTrue($this->parser->getHasNextPage());
        self::assertSame(2, $this->parser->getLastPage());
        self::assertSame(2, $this->top->getLastVisiblePage());
    }
}