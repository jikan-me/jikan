<?php

namespace JikanTest\Parser\SeasonList;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\SeasonList\SeasonListItemParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SeasonListItemParserTest extends TestCase
{
    /**
     * @var SeasonListItemParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/season/archive');
        $this->parser = new SeasonListItemParser(
            $crawler->filterXPath('//table[contains(@class, "anime-seasonal-byseason")]/tr')->first()
        );
    }

    #[Test]
    public function it_gets_the_year(): void
    {
        self::assertEquals(2024, $this->parser->getYear());
    }

    #[Test]
    public function it_gets_the_seasons(): void
    {
        self::assertContains('winter', $this->parser->getSeasons());
    }
}
