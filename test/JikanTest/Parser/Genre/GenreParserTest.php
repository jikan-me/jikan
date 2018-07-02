<?php

namespace JikanTest\Parser\Person;

use Goutte\Client;
use Jikan\Model\AnimeCard;
use Jikan\Parser\Genre\GenreParser;
use PHPUnit\Framework\TestCase;

/**
 * Class GenreParserTest
 */
class GenreParserTest extends TestCase
{
    /**
     * @var GenreParser
     */
    private $parser;

    public function setUp()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/genre/1');
        $this->parser = new GenreParser($crawler);
    }

    /**
     * @test
     * @vcr GenreParserTest.yaml
     */
    public function it_gets_url()
    {
        $url = $this->parser->getUrl();
        self::assertInstanceOf(\Jikan\Model\MalUrl::class, $url);
    }

    /**
     * @test
     * @vcr GenreParserTest.yaml
     */
    public function it_gets_anime()
    {
        $anime = $this->parser->getGenreAnime();
        self::assertCount(100, $anime);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\AnimeCard::class, $anime);
    }
}
