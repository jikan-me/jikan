<?php

namespace JikanTest\Parser\Genre;

use Goutte\Client;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\Common\MangaCard;
use Jikan\Parser\Genre\MangaGenreParser;
use PHPUnit\Framework\TestCase;

/**
 * Class MangaGenreParserTest
 */
class MangaGenreParserTest extends TestCase
{
    /**
     * @var MangaGenreParser
     */
    private $parser;

    public function setUp()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/manga/genre/1');
        $this->parser = new MangaGenreParser($crawler);
    }

    /**
     * @test
     * @vcr MangaGenreParserTest.yaml
     */
    public function it_gets_url()
    {
        $url = $this->parser->getUrl();
        self::assertEquals("https://myanimelist.net/manga/genre/1/Action", $url);
    }

    /**
     * @test
     * @vcr MangaGenreParserTest.yaml
     */
    public function it_gets_manga()
    {
        $manga = $this->parser->getGenreManga();
        self::assertContainsOnlyInstancesOf(MangaCard::class, $manga);
    }

    /**
     * @test
     * @vcr MangaGenreParserTest.yaml
     */
    public function it_gets_the_count()
    {
        self::assertEquals(6187, $this->parser->getCount());
    }
}
