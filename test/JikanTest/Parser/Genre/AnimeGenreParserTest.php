<?php

namespace JikanTest\Parser\Genre;

use Goutte\Client;
use Jikan\Model\Common\AnimeCard;
use Jikan\Parser\Genre\AnimeGenreParser;
use PHPUnit\Framework\TestCase;

/**
 * Class AnimeGenreParserTest
 */
class AnimeGenreParserTest extends TestCase
{
    /**
     * @var AnimeGenreParser
     */
    private $parser;

    public function setUp()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/genre/1');
        $this->parser = new AnimeGenreParser($crawler);
    }

    /**
     * @test
     * @vcr AnimeGenreParserTest.yaml
     */
    public function it_gets_url()
    {
        $url = $this->parser->getUrl();
        self::assertEquals("https://myanimelist.net/anime/genre/1/Action", $url);
    }

    /**
     * @test
     * @vcr AnimeGenreParserTest.yaml
     */
    public function it_gets_anime()
    {
        $anime = $this->parser->getGenreAnime();
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $anime);
    }

    /**
     * @test
     * @vcr AnimeGenreParserTest.yaml
     */
    public function it_gets_the_count()
    {
        self::assertEquals(3263, $this->parser->getCount());
    }
}
