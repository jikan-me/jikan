<?php

namespace JikanTest\Parser\Pictures;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
use Jikan\Parser\Common\PicturesPageParser;
use JikanTest\TestCase;

/**
 * Class PicturesPageParserTest
 */
class PicturesPageParserTest extends TestCase
{
    /**
     * @var PicturesPageParser
     */
    private PicturesPageParser $animeParser;

    /**
     * @var PicturesPageParser
     */
    private PicturesPageParser $mangaParser;

    /**
     * @var PicturesPageParser
     */
    private PicturesPageParser $personParser;

    /**
     * @var PicturesPageParser
     */
    private PicturesPageParser $characterParser;

    /**
     * @test
     */
    public function it_gets_manga_pictures()
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/manga/50145/jikan/pics');
        $this->mangaParser = new PicturesPageParser($crawler);
        $pictures = $this->mangaParser->getModel();

        self::assertGreaterThan(0, count($pictures));
        self::assertContainsOnlyInstancesOf(CommonImageResource::class, $pictures);
        self::assertStringContainsString(
            'https://cdn.myanimelist.net/images/manga/',
            $pictures[0]->getJpg()->getSmallImageUrl()
        );
        self::assertStringContainsString(
            'https://cdn.myanimelist.net/images/manga/',
            $pictures[0]->getJpg()->getLargeImageUrl()
        );
    }

    /**
     * @test
     */
    public function it_gets_anime_pictures()
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/22147/jikan/pics');
        $this->animeParser = new PicturesPageParser($crawler);
        $pictures = $this->animeParser->getModel();

        self::assertGreaterThan(0, count($pictures));
        self::assertContainsOnlyInstancesOf(CommonImageResource::class, $pictures);
        self::assertStringContainsString(
            'https://cdn.myanimelist.net/images/anime/',
            $pictures[0]->getJpg()->getSmallImageUrl()
        );
        self::assertStringContainsString(
            'https://cdn.myanimelist.net/images/anime/',
            $pictures[0]->getJpg()->getLargeImageUrl()
        );
    }

    /**
     * @test
     */
    public function it_gets_person_pictures()
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/people/11162/jikan/pics');
        $this->personParser = new PicturesPageParser($crawler);
        $pictures = $this->personParser->getModel();

        self::assertGreaterThan(0, count($pictures));
        self::assertContainsOnlyInstancesOf(CommonImageResource::class, $pictures);
        self::assertStringContainsString(
            'https://cdn.myanimelist.net/images/voiceactors/',
            $pictures[0]->getJpg()->getSmallImageUrl()
        );
        self::assertStringContainsString(
            'https://cdn.myanimelist.net/images/voiceactors/',
            $pictures[0]->getJpg()->getLargeImageUrl()
        );
    }

    /**
     * @test
     */
    public function it_gets_character_pictures()
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/character/105591/jikan/pics');
        $this->characterParser = new PicturesPageParser($crawler);
        $pictures = $this->characterParser->getModel();

        self::assertGreaterThan(0, count($pictures));
        self::assertContainsOnlyInstancesOf(CommonImageResource::class, $pictures);
        self::assertStringContainsString(
            'https://cdn.myanimelist.net/images/characters/',
            $pictures[0]->getJpg()->getSmallImageUrl()
        );
        self::assertStringContainsString(
            'https://cdn.myanimelist.net/images/characters/',
            $pictures[0]->getJpg()->getLargeImageUrl()
        );
    }
}
