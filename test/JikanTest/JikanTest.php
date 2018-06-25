<?php

use Jikan\Jikan;
use PHPUnit\Framework\TestCase;

/**
 * Class JikanTest
 */
class JikanTest extends TestCase
{
    /**
     * @var Jikan
     */
    private $jikan;

    public function setUp()
    {
        $this->jikan = new Jikan();
    }

    /**
     * @test
     * @vcr JikanTest_it_gets_anime.yaml
     */
    public function it_gets_anime()
    {
        $response = $this->jikan->Anime(21);
        self::assertNotNull($response);
    }

    /**
     * @test
     * @vcr JikanTest_it_gets_anime_episodes.yaml
     */
    public function it_gets_anime_episodes()
    {
        $response = $this->jikan->Anime(21, [EPISODES]);
        self::assertNotNull($response);
    }

    /**
     * @test
     * @vcr JikanTest_it_gets_anime_characters_and_staff.yaml
     */
    public function it_gets_anime_characters_and_staff()
    {
        $response = $this->jikan->Anime(21, [CHARACTERS_STAFF]);
        self::assertNotNull($response);
    }

    /**
     * @test
     * @vcr JikanTest_it_gets_anime_news.yaml
     */
    public function it_gets_anime_news()
    {
        $response = $this->jikan->Anime(21, [NEWS]);
        self::assertNotNull($response);
    }

    /**
     * @test
     * @vcr JikanTest_it_gets_manga.yaml
     */
    public function it_gets_manga()
    {
        $response = $this->jikan->Manga(1);
        self::assertNotNull($response);
    }

    /**
     * @test
     * @vcr JikanTest_it_gets_manga_characters.yaml
     */
    public function it_gets_manga_characters()
    {
        $response = $this->jikan->Manga(1, [CHARACTERS]);
        self::assertNotNull($response);
    }

    /**
     * @test
     * @vcr JikanTest_it_gets_manga_news.yaml
     */
    public function it_gets_manga_news()
    {
        $respose = $this->jikan->Manga(1, [NEWS]);
        self::assertNotNull($respose);
    }

    /**
     * @test
     * @vcr JikanTest_it_gets_persons.yaml
     */
    public function it_gets_persons()
    {
        $response = $this->jikan->Manga(1, [NEWS]);
        self::assertNotNull($response);
    }

    /**
     * @test
     * @vcr JikanTest_it_gets_characters.yaml
     */
    public function it_gets_characters()
    {
        self::markTestSkipped('MAL Characters are still down');
        $response = $this->jikan->Character(1);
        self::assertNotNull($response);
    }

    /**
     * @test
     * @vcr JikanTest_it_gets_seasonal.yaml
     */
    public function it_gets_seasonal()
    {
        $response = $this->jikan->Seasonal(SPRING, 2018);
        self::assertNotNull($response);
    }
}
