<?php

namespace JikanTest;

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
        $anime = $this->jikan->Anime(new \Jikan\Request\Anime(21));
        self::assertInstanceOf(\Jikan\Model\Anime::class, $anime);
    }

    /**
     * @test
     * @vcr JikanTest_it_gets_manga.yaml
     */
    public function it_gets_manga()
    {
        $manga = $this->jikan->Manga(new \Jikan\Request\Manga(11));
        self::assertInstanceOf(\Jikan\Model\Manga::class, $manga);
    }

    /**
     * @test
     * @vcr CharacterParserTest.yaml
     */
    public function it_gets_characters()
    {
        $character = $this->jikan->Character(new \Jikan\Request\Character(116281));
        self::assertInstanceOf(\Jikan\Model\Character::class, $character);
        self::assertCount(9, $character->getAnimeography());
        self::assertCount(2, $character->getMangaography());
        self::assertCount(4, $character->getVoiceActors());
    }

    /**
     * @test
     * @vcr PersonParserTest.yaml
     */
    public function it_gets_person()
    {
        $person = $this->jikan->Person(new \Jikan\Request\Person(1));
        self::assertInstanceOf(\Jikan\Model\Person::class, $person);
        self::assertCount(367, $person->getVoiceActingRoles());
        self::assertCount(15, $person->getAnimeStaffPositions());
        self::assertCount(0, $person->getPublishedManga());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_seasonal_anime()
    {
        $seasonal = $this->jikan->Seasonal(new \Jikan\Request\Seasonal(2018, 'spring'));
        self::assertInstanceOf(\Jikan\Model\Seasonal::class, $seasonal);
        self::assertCount(234, $seasonal->getAnime());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\SeasonalAnime::class, $seasonal->getAnime());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_user_profile()
    {
        $user = $this->jikan->UserProfile(new \Jikan\Request\UserProfile('sandshark'));
        self::assertInstanceOf(\Jikan\Model\UserProfile::class, $user);
        self::assertInstanceOf(\Jikan\Model\AnimeStats::class, $user->getAnimeStats());
        self::assertInstanceOf(\Jikan\Model\MangaStats::class, $user->getMangaStats());
    }
}
