<?php

namespace JikanTest;

use Jikan\Exception\BadResponseException;
use Jikan\MyAnimeList\MalClient;
use Jikan\Model\Forum\ForumTopic;
use Jikan\Model\News\NewsListItem;
use Jikan\Model\Top\TopAnime;
use Jikan\Model\Top\TopCharacter;
use Jikan\Model\Top\TopManga;
use Jikan\Model\Top\TopPerson;
use Jikan\Model\User\Friend;
use Jikan\Request\Anime\AnimeForumRequest;
use Jikan\Request\Manga\MangaForumRequest;
use Jikan\Request\Anime\AnimeNewsRequest;
use Jikan\Request\Manga\MangaNewsRequest;
use PHPUnit\Framework\TestCase;

/**
 * Class JikanTest
 */
class JikanTest extends TestCase
{
    /**
     * @var MalClient
     */
    private $jikan;

    public function setUp()
    {
        $this->jikan = new MalClient;
    }

    /**
     * @test
     * @vcr JikanTest_it_gets_anime.yaml
     */
    public function it_gets_anime()
    {
        $anime = $this->jikan->getAnime(new \Jikan\Request\Anime\AnimeRequest(21));
        self::assertInstanceOf(\Jikan\Model\Anime\Anime::class, $anime);
    }

    /**
     * @test
     * @vcr JikanTest_it_gets_manga.yaml
     */
    public function it_gets_manga()
    {
        $manga = $this->jikan->getManga(new \Jikan\Request\Manga\MangaRequest(11));
        self::assertInstanceOf(\Jikan\Model\Manga\Manga::class, $manga);
    }

    /**
     * @test
     * @vcr CharacterParserTest.yaml
     */
    public function it_gets_characters()
    {
        $character = $this->jikan->getCharacter(new \Jikan\Request\Character\CharacterRequest(116281));
        self::assertInstanceOf(\Jikan\Model\Character\Character::class, $character);
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
        $person = $this->jikan->getPerson(new \Jikan\Request\Person\PersonRequest(1));
        self::assertInstanceOf(\Jikan\Model\Person\Person::class, $person);
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
        $seasonal = $this->jikan->getSeasonal(new \Jikan\Request\Seasonal\SeasonalRequest(2018, 'spring'));
        self::assertInstanceOf(\Jikan\Model\Seasonal\Seasonal::class, $seasonal);
        self::assertCount(234, $seasonal->getAnime());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Seasonal\SeasonalAnime::class, $seasonal->getAnime());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_user_profile()
    {
        $user = $this->jikan->getUserProfile(new \Jikan\Request\User\UserProfileRequest('sandshark'));
        self::assertInstanceOf(\Jikan\Model\User\Profile::class, $user);
        self::assertInstanceOf(\Jikan\Model\User\AnimeStats::class, $user->getAnimeStats());
        self::assertInstanceOf(\Jikan\Model\User\MangaStats::class, $user->getMangaStats());
    }

    /**
     * @test
     * @vcr ScheduleParserTest.yaml
     */
    public function it_gets_schedule()
    {
        $schedule = $this->jikan->getSchedule(new \Jikan\Request\Schedule\ScheduleRequest());
        self::assertInstanceOf(\Jikan\Model\Schedule\Schedule::class, $schedule);
    }

    /**
     * @test
     * @vcr FriendsParserTest.yaml
     */
    public function it_gets_friends()
    {
        $friends = $this->jikan->getUserFriends(new \Jikan\Request\User\UserFriendsRequest('morshuwarrior'));
        self::assertContainsOnlyInstancesOf(Friend::class, $friends);
        self::assertCount(100, $friends);
        self::assertContains('sandshark', $friends);
        self::assertContains('mangalicker94', $friends);
        self::assertContains('Moune-Chan', $friends);

        // Second page
        $friends = $this->jikan->getUserFriends(new \Jikan\Request\User\UserFriendsRequest('morshuwarrior', 1));
        self::assertContainsOnlyInstancesOf(Friend::class, $friends);
        self::assertCount(100, $friends);
        self::assertContains('Benku', $friends);
        self::assertContains('Seiya', $friends);

        // Empty page
        // Second
        self::expectException(BadResponseException::class);
        $this->jikan->getUserFriends(new \Jikan\Request\User\UserFriendsRequest('morshuwarrior', 100));
    }

    /**
     * @test
     * @vcr ProducerParserTest.yaml
     */
    public function it_gets_producer()
    {
        $producer = $this->jikan->getProducer(new \Jikan\Request\Producer\ProducerRequest(1));
        self::assertInstanceOf(\Jikan\Model\Producer\Producer::class, $producer);
    }

    /**
     * @test
     * @vcr CharactersAndStaffParserTest.yaml
     */
    public function it_gets_characters_and_staff()
    {
        $charactersAndStaff = $this->jikan->getAnimeCharactersAndStaff(
            new \Jikan\Request\Anime\AnimeCharactersAndStaffRequest(35073)
        );
        $staff = $charactersAndStaff->getStaff();
        $characters = $charactersAndStaff->getCharacters();
        self::assertCount(53, $characters);
        self::assertCount(13, $staff);
    }

    /**
     * @test
     * @vcr AnimeVideosParserTest.yaml
     */
    public function it_gets_anime_videos()
    {
        $videos = $this->jikan->getAnimeVideos(new \Jikan\Request\Anime\AnimeVideosRequest(1));
        $promos = $videos->getPromos();
        $episodes = $videos->getEpisodes();
        self::assertCount(3, $promos);
        self::assertCount(26, $episodes);
    }

    /**
     * @test
     * @vcr AnimePicturesParserTest.yaml
     */
    public function it_gets_anime_pictures()
    {
        $pictures = $this->jikan->getAnimePictures(new \Jikan\Request\Anime\AnimePicturesRequest(1));
        self::assertCount(10, $pictures);
    }

    /**
     * @test
     * @vcr MangaPicturesParserTest.yaml
     */
    public function it_gets_manga_pictures()
    {
        $pictures = $this->jikan->getMangaPictures(new \Jikan\Request\Manga\MangaPicturesRequest(1));
        self::assertCount(8, $pictures);
    }

    /**
     * @test
     * @vcr PersonPicturesParserTest.yaml
     */
    public function it_gets_person_pictures()
    {
        $pictures = $this->jikan->getPersonPictures(new \Jikan\Request\Person\PersonPicturesRequest(1));
        self::assertCount(4, $pictures);
    }

    /**
     * @test
     * @vcr CharacterPicturesParserTest.yaml
     */
    public function it_gets_character_pictures()
    {
        $pictures = $this->jikan->getCharacterPictures(new \Jikan\Request\Character\CharacterPicturesRequest(1));
        self::assertCount(15, $pictures);
    }

    /**
     * @test
     * @vcr MangaNewsParserTest.yaml
     */
    public function it_gets_manga_news()
    {
        $items = $this->jikan->getNewsList(new MangaNewsRequest(2));
        self::assertCount(14, $items);
        self::assertContainsOnlyInstancesOf(NewsListItem::class, $items);
    }

    /**
     * @test
     * @vcr AnimeNewsParserTest.yaml
     */
    public function it_gets_anime_news()
    {
        $items = $this->jikan->getNewsList(new AnimeNewsRequest(21));
        self::assertCount(30, $items);
        self::assertContainsOnlyInstancesOf(NewsListItem::class, $items);
    }

    /**
     * @test
     * @vcr AnimeSearchParserTest.yaml
     */
    public function it_gets_anime_search()
    {
        $search = $this->jikan->getAnimeSearch(new \Jikan\Request\Search\AnimeSearchRequest('Fate'));
        self::assertCount(50, $search->getResults());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Search\AnimeSearchListItem::class, $search->getResults());
        self::assertEquals(17, $search->getLastPage());
    }

    /**
     * @test
     * @vcr MangaSearchParserTest.yaml
     */
    public function it_gets_manga_search()
    {
        $search = $this->jikan->getMangaSearch(new \Jikan\Request\Search\MangaSearchRequest('Fate'));
        self::assertCount(50, $search->getResults());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Search\MangaSearchListItem::class, $search->getResults());
        self::assertEquals(20, $search->getLastPage());
    }

    /**
     * @test
     * @vcr CharacterSearchParserTest.yaml
     */
    public function it_gets_character_search()
    {
        $search = $this->jikan->getCharacterSearch(new \Jikan\Request\Search\CharacterSearchRequest('Fate'));
        self::assertCount(50, $search->getResults());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Search\CharacterSearchListItem::class, $search->getResults());
        self::assertEquals(11, $search->getLastPage());
    }

    /**
     * @test
     * @vcr PersonSearchParserTest.yaml
     */
    public function it_gets_person_search()
    {
        $search = $this->jikan->getPersonSearch(new \Jikan\Request\Search\PersonSearchRequest('Ara'));
        self::assertCount(50, $search->getResults());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Search\PersonSearchListItem::class, $search->getResults());
        self::assertEquals(20, $search->getLastPage());
    }

    /**
     * @test
     * @vcr MangaCharacterListParserTest.yaml
     */
    public function it_gets_manga_characters()
    {
        $characters = $this->jikan->getMangaCharacters(new \Jikan\Request\Manga\MangaCharactersRequest(2));
        self::assertCount(70, $characters);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Manga\CharacterListItem::class, $characters);
    }

    /**
     * @test
     * @vcr TopAnimeParserTest.yaml
     */
    public function it_gets_top_anime()
    {
        $anime = $this->jikan->getTopAnime(new \Jikan\Request\Top\TopAnimeRequest());
        self::assertCount(50, $anime);
        self::assertContainsOnlyInstancesOf(TopAnime::class, $anime);
        self::assertContains('Fullmetal Alchemist: Brotherhood', $anime);
        self::assertContains('Mushishi Zoku Shou: Suzu no Shizuku', $anime);
    }

    /**
     * @test
     * @vcr TopMangaParserTest.yaml
     */
    public function it_gets_top_manga()
    {
        $manga = $this->jikan->getTopManga(new \Jikan\Request\Top\TopMangaRequest());
        self::assertCount(50, $manga);
        self::assertContainsOnlyInstancesOf(TopManga::class, $manga);
        self::assertContains('Berserk', $manga);
        self::assertContains('Shigatsu wa Kimi no Uso', $manga);
    }

    /**
     * @test
     * @vcr TopCharacterParserTest.yaml
     */
    public function it_gets_top_characters()
    {
        $characters = $this->jikan->getTopCharacters(new \Jikan\Request\Top\TopCharactersRequest());
        self::assertCount(50, $characters);
        self::assertContainsOnlyInstancesOf(TopCharacter::class, $characters);
        self::assertContains('Lamperouge, Lelouch', $characters);
        self::assertContains('Usui, Takumi', $characters);
    }

    /**
     * @test
     * @vcr TopPeopleParserTest.yaml
     */
    public function it_gets_top_people()
    {
        $people = $this->jikan->getTopPeople(new \Jikan\Request\Top\TopPeopleRequest());
        self::assertCount(50, $people);
        self::assertContainsOnlyInstancesOf(TopPerson::class, $people);
        self::assertContains('Hanazawa, Kana', $people);
        self::assertContains('Asano, Inio', $people);
    }

    /**
     * @test
     * @vcr ForumTopicParserTest.yaml
     */
    public function it_gets_anime_forum()
    {
        $topics = $this->jikan->getAnimeForum(new AnimeForumRequest(21));
        self::assertCount(15, $topics);
        self::assertContainsOnlyInstancesOf(ForumTopic::class, $topics);
        self::assertContains('One Piece Episode 381 Discussion', $topics);
        self::assertContains('One Piece Episode 380 Discussion', $topics);
    }

    /**
     * @test
     * @vcr MangaForumTopicParserTest.yaml
     */
    public function it_gets_manga_forum()
    {
        $topics = $this->jikan->getMangaForum(new MangaForumRequest(21));
        self::assertCount(15, $topics);
        self::assertContainsOnlyInstancesOf(ForumTopic::class, $topics);
        self::assertContains('Death Note Chapter 60 Discussion', $topics);
        self::assertContains('Death Note Chapter 21 Discussion', $topics);
    }

    /**
     * @test
     * @vcr HistoryParserTest.yaml
     */
    public function it_gets_user_history()
    {
        $history = $this->jikan->getUserHistory(new \Jikan\Request\User\UserHistoryRequest('nekomata1037'));
        self::assertCount(17, $history);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\User\History::class, $history);
    }

    /**
     * @test
     * @vcr AnimeEpisodesParserTest.yaml
     */
    public function it_gets_anime_episodes()
    {
        $episodes = $this->jikan->getAnimeEpisodes(new \Jikan\Request\Anime\AnimeEpisodesRequest(21));
        self::assertCount(100, $episodes->getEpisodes());
        self::assertEquals(9, $episodes->getEpisodesLastPage());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Anime\EpisodeListItem::class, $episodes->getEpisodes());
    }
}
