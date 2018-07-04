<?php

namespace JikanTest;

use Jikan\Jikan;
use Jikan\Model\Friend;
use Jikan\Request\CharactersAndStaff;
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

    /**
     * @test
     * @vcr ScheduleParserTest.yaml
     */
    public function it_gets_schedule()
    {
        $schedule = $this->jikan->Schedule(new \Jikan\Request\Schedule());
        self::assertInstanceOf(\Jikan\Model\Schedule::class, $schedule);
    }

    /**
     * @test
     * @vcr FriendsParserTest.yaml
     */
    public function it_gets_friends()
    {
        $friends = $this->jikan->Friends(new \Jikan\Request\UserFriends('morshuwarrior'));
        self::assertContainsOnlyInstancesOf(Friend::class, $friends);
        self::assertCount(100, $friends);
        self::assertContains('sandshark', $friends);
        self::assertContains('mangalicker94', $friends);
        self::assertContains('Moune-Chan', $friends);

        // Second page
        $friends = $this->jikan->Friends(new \Jikan\Request\UserFriends('morshuwarrior', 1));
        self::assertContainsOnlyInstancesOf(Friend::class, $friends);
        self::assertCount(100, $friends);
        self::assertContains('sword123', $friends);
        self::assertContains('Impactaction', $friends);

        // Empty page
        // Second page
        $friends = $this->jikan->Friends(new \Jikan\Request\UserFriends('morshuwarrior', 100));
        self::assertContainsOnlyInstancesOf(Friend::class, $friends);
        self::assertCount(0, $friends);
    }

    /**
     * @test
     * @vcr ProducerParserTest.yaml
     */
    public function it_gets_producer()
    {
        $producer = $this->jikan->Producer(new \Jikan\Request\Producer(1));
        self::assertInstanceOf(\Jikan\Model\Producer::class, $producer);
    }

    /**
     * @test
     * @vcr CharactersParserTest.yaml
     */
    public function it_gets_characters_and_staff()
    {
        $charactersAndStaff = $this->jikan->CharactersAndStaff(new CharactersAndStaff(35073));
        $staff = $charactersAndStaff->getStaff();
        $characters = $charactersAndStaff->getCharacters();
        self::assertCount(53, $characters);
        self::assertCount(13, $staff);
    }
}
