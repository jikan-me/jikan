<?php

namespace JikanTest\Parser\Pictures;

use Goutte\Client;
use Jikan\Model\Common\Picture;
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

    /**
     * @test
     * @vcr MangaPictures.yaml
     */
    public function it_gets_manga_pictures()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/manga/50145/jikan/pics');
        $this->mangaParser = new PicturesPageParser($crawler);
        $pictures = $this->mangaParser->getModel();

        self::assertGreaterThan(0, count($pictures));
        self::assertInstanceOf(Picture::class, $pictures[0]);
        self::assertContains('https://myanimelist.cdn-dena.com/images/manga/', $pictures[0]->getSmall());
        self::assertContains('https://myanimelist.cdn-dena.com/images/manga/', $pictures[0]->getLarge());
    }

    /**
     * @test
     * @vcr AnimePictures.yaml
     */
    public function it_gets_anime_pictures()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/22147/jikan/pics');
        $this->animeParser = new PicturesPageParser($crawler);
        $pictures = $this->animeParser->getModel();

        self::assertGreaterThan(0, count($pictures));
        self::assertInstanceOf(Picture::class, $pictures[0]);
        self::assertContains('https://myanimelist.cdn-dena.com/images/anime/', $pictures[0]->getSmall());
        self::assertContains('https://myanimelist.cdn-dena.com/images/anime/', $pictures[0]->getLarge());
    }

    /**
     * @test
     * @vcr Peopleictures.yaml
     */
    public function it_gets_person_pictures()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/people/11162/jikan/pictures');
        $this->personParser = new PicturesPageParser($crawler);
        $pictures = $this->personParser->getModel();

        self::assertGreaterThan(0, count($pictures));
        self::assertInstanceOf(Picture::class, $pictures[0]);
        self::assertContains('https://myanimelist.cdn-dena.com/images/voiceactors/', $pictures[0]->getSmall());
        self::assertContains('https://myanimelist.cdn-dena.com/images/voiceactors/', $pictures[0]->getLarge());
    }

    /**
     * @test
     * @vcr CharacterPictures.yaml
     */
    public function it_gets_character_pictures()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/character/105591/jikan/pictures');
        $this->characterParser = new PicturesPageParser($crawler);
        $pictures = $this->characterParser->getModel();

        self::assertGreaterThan(0, count($pictures));
        self::assertInstanceOf(Picture::class, $pictures[0]);
        self::assertContains('https://myanimelist.cdn-dena.com/images/characters/', $pictures[0]->getSmall());
        self::assertContains('https://myanimelist.cdn-dena.com/images/characters/', $pictures[0]->getLarge());
    }
}
