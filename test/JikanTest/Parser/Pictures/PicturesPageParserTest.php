<?php

namespace JikanTest\Parser\Person;

use Goutte\Client;
use Jikan\Model\Picture;
use Jikan\Parser\Common\PicturesPageParser;
use PHPUnit\Framework\TestCase;

/**
 * Class PicturesPageParserTest
 */
class PicturesPageParserTest extends TestCase
{
    /**
     * @var PicturesPageParser
     */
    private $animeParser;

    /**
     * @var PicturesPageParser
     */
    private $mangaParser;

    /**
     * @var PicturesPageParser
     */
    private $personParser;

    /**
     * @var PicturesPageParser
     */
    private $characterParser;

    public function setUp()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/22147/jikan/pics');
        $this->animeParser = new PicturesPageParser($crawler);

        $crawler = $client->request('GET', 'https://myanimelist.net/manga/50145/jikan/pics');
        $this->mangaParser = new PicturesPageParser($crawler);

        $crawler = $client->request('GET', 'https://myanimelist.net/people/11162/jikan/pictures');
        $this->personParser = new PicturesPageParser($crawler);

        $crawler = $client->request('GET', 'https://myanimelist.net/character/105591/jikan/pictures');
        $this->characterParser = new PicturesPageParser($crawler);
    }

    /**
     * @test
     * @vcr PicturesPageParserTest.yaml
     */
    public function it_gets_manga_pictures()
    {
        $pictures = $this->mangaParser->getModel();

        self::assertGreaterThan(0, count($pictures));
        self::assertInstanceOf(Picture::class, $pictures[0]);
        self::assertContains('https://myanimelist.cdn-dena.com/images/manga/', $pictures[0]->getSmall());
        self::assertContains('https://myanimelist.cdn-dena.com/images/manga/', $pictures[0]->getLarge());
    }

    /**
     * @test
     * @vcr AnimeGenreParserTest.yaml
     */
    public function it_gets_anime_pictures()
    {
        $pictures = $this->animeParser->getModel();

        self::assertGreaterThan(0, count($pictures));
        self::assertInstanceOf(Picture::class, $pictures[0]);
        self::assertContains('https://myanimelist.cdn-dena.com/images/anime/', $pictures[0]->getSmall());
        self::assertContains('https://myanimelist.cdn-dena.com/images/anime/', $pictures[0]->getLarge());
    }

    /**
     * @test
     * @vcr AnimeGenreParserTest.yaml
     */
    public function it_gets_person_pictures()
    {
        $pictures = $this->personParser->getModel();

        self::assertGreaterThan(0, count($pictures));
        self::assertInstanceOf(Picture::class, $pictures[0]);
        self::assertContains('https://myanimelist.cdn-dena.com/images/voiceactors/', $pictures[0]->getSmall());
        self::assertContains('https://myanimelist.cdn-dena.com/images/voiceactors/', $pictures[0]->getLarge());
    }

    /**
     * @test
     * @vcr AnimeGenreParserTest.yaml
     */
    public function it_gets_character_pictures()
    {
        $pictures = $this->characterParser->getModel();

        self::assertGreaterThan(0, count($pictures));
        self::assertInstanceOf(Picture::class, $pictures[0]);
        self::assertContains('https://myanimelist.cdn-dena.com/images/characters/', $pictures[0]->getSmall());
        self::assertContains('https://myanimelist.cdn-dena.com/images/characters/', $pictures[0]->getLarge());
    }
}
