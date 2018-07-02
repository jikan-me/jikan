<?php

namespace JikanTest\Parser\Person;

use Goutte\Client;
use Jikan\Model\MangaCard;
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
        $crawler = $client->request('GET', 'https://myMangalist.net/manga/genre/1');
        $this->parser = new MangaGenreParser($crawler);
    }

    /**
     * @test
     * @vcr MangaGenreParserTest.yaml
     */
    public function it_gets_url()
    {
        $url = $this->parser->getUrl();
        self::assertInstanceOf(\Jikan\Model\MalUrl::class, $url);
    }

    /**
     * @test
     * @vcr MangaGenreParserTest.yaml
     */
    public function it_gets_Manga()
    {
        $Manga = $this->parser->getGenreManga();
        self::assertCount(100, $Manga);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\MangaCard::class, $Manga);
    }
}
