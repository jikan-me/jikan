<?php

namespace JikanTest\Parser\Genre;

use Goutte\Client;
use Jikan\Model\Common\AnimeCard;
use Jikan\Parser\Genre\AnimeGenreParser;
use JikanTest\TestCase;

/**
 * Class AnimeGenreParserTest
 */
class AnimeGenreParserTest extends TestCase
{
    /**
     * @var AnimeGenreParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new Client($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/genre/1');
        $this->parser = new AnimeGenreParser($crawler);
    }

    /**
     * @test
     */
    public function it_gets_url()
    {
        $url = $this->parser->getUrl();
        self::assertEquals("https://myanimelist.net/anime/genre/1/Action", $url);
    }

    /**
     * @test
     */
    public function it_gets_anime()
    {
        $anime = $this->parser->getResults();
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $anime);
    }

    /**
     * @test
     */
    public function it_gets_the_count()
    {
        self::assertEquals(4210, $this->parser->getCount());
    }
}
