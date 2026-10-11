<?php

namespace JikanTest\Parser\Search;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Search\CharacterSearchParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class CharacterSearchParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Search\CharacterSearchParser
 * (getCharacterSearch), driving the search-query page through the endpoint
 * parser (mirrors the AnimeSearchParserTest pattern).
 */
class CharacterSearchParserTest extends TestCase
{
    /**
     * @var CharacterSearchParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Search\CharacterSearchRequest('Luffy');
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new CharacterSearchParser($crawler);
    }

    #[Test]
    public function it_parses_the_full_result_page(): void
    {
        self::assertCount(13, $this->parser->getResults());
    }

    #[Test]
    public function it_parses_the_first_result(): void
    {
        $result = $this->parser->getResults()[0];
        self::assertSame(40, $result->getMalId());
        self::assertSame('Monkey D., Luffy', $result->getName());
        self::assertSame(['Mugiwara', 'Straw Hat'], $result->getAlternativeNames());
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/images/characters/.*~',
            (string) $result->getImages()->getJpg()->getImageUrl()
        );
    }

    #[Test]
    public function it_reports_search_pagination(): void
    {
        self::assertFalse($this->parser->getHasNextPage());
        self::assertSame(1, $this->parser->getLastPage());
    }
}