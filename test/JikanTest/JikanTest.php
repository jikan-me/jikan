<?php

namespace JikanTest;

use DateTimeImmutable;
use Jikan\Exception\BadResponseException;
use Jikan\Helper\Constants;
use Jikan\MyAnimeList\MalClient;
use Jikan\Model\Forum\ForumTopic;
use Jikan\Model\News\NewsListItem;
use Jikan\Model\Top\TopAnimeListItem;
use Jikan\Model\Top\TopCharacterListItem;
use Jikan\Model\Top\TopMangaListItem;
use Jikan\Model\Top\TopPersonListItem;
use Jikan\Model\User\Friend;
use Jikan\Request\Anime\AnimeForumRequest;
use Jikan\Request\Manga\MangaForumRequest;
use Jikan\Request\Anime\AnimeNewsRequest;
use Jikan\Request\Manga\MangaNewsRequest;

/**
 * Class JikanTest
 */
class JikanTest extends TestCase
{
    /**
     * @var MalClient
     */
    private $jikan;

    public function setUp(): void
    {
        parent::setUp();

        $this->jikan = new MalClient($this->httpClient);
    }

    /**
     * @test
     */
    public function it_gets_anime()
    {
        $anime = $this->jikan->getAnime(new \Jikan\Request\Anime\AnimeRequest(21));
        self::assertInstanceOf(\Jikan\Model\Anime\Anime::class, $anime);
    }

    /**
     * @test
     */
    public function it_gets_unapproved_anime()
    {
        $anime = $this->jikan->getAnime(new \Jikan\Request\Anime\AnimeRequest(48104));
        self::assertEquals(false, $anime->isApproved());
    }

    /**
     * @test
     */
    public function it_gets_manga()
    {
        $manga = $this->jikan->getManga(new \Jikan\Request\Manga\MangaRequest(11));
        self::assertInstanceOf(\Jikan\Model\Manga\Manga::class, $manga);
    }

    /**
     * @test
     */
    public function it_gets_unapproved_manga()
    {
        $manga = $this->jikan->getManga(new \Jikan\Request\Manga\MangaRequest(145036));
        self::assertEquals(false, $manga->isApproved());
    }

    /**
     * @test
     */
    public function it_gets_characters()
    {
        $character = $this->jikan->getCharacter(new \Jikan\Request\Character\CharacterRequest(116281));
        self::assertInstanceOf(\Jikan\Model\Character\Character::class, $character);
        self::assertCount(14, $character->getAnimeography());
        self::assertCount(2, $character->getMangaography());
        self::assertCount(7, $character->getVoiceActors());
    }

    /**
     * @test
     */
    public function it_gets_person()
    {
        $person = $this->jikan->getPerson(new \Jikan\Request\Person\PersonRequest(1));
        self::assertInstanceOf(\Jikan\Model\Person\Person::class, $person);
        self::assertCount(420, $person->getVoiceActingRoles());
        self::assertCount(15, $person->getAnimeStaffPositions());
        self::assertCount(0, $person->getPublishedManga());
    }

    /**
     * @test
     */
    public function it_gets_seasonal_anime()
    {
        $seasonal = $this->jikan->getSeasonal(new \Jikan\Request\Seasonal\SeasonalRequest(2018, 'spring'));
        self::assertInstanceOf(\Jikan\Model\Seasonal\Seasonal::class, $seasonal);
        self::assertCount(279, $seasonal->getAnime());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Seasonal\SeasonalAnime::class, $seasonal->getAnime());
    }

    /**
     * @test
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
     */
    public function it_gets_schedule()
    {
        $schedule = $this->jikan->getSchedule(new \Jikan\Request\Schedule\ScheduleRequest());
        self::assertInstanceOf(\Jikan\Model\Schedule\Schedule::class, $schedule);
    }

    /**
     * @test
     */
    public function it_gets_friends()
    {
        $friends = $this->jikan->getUserFriends(new \Jikan\Request\User\UserFriendsRequest('morshuwarrior'));
        self::assertContainsOnlyInstancesOf(Friend::class, $friends->getResults());
        self::assertCount(100, $friends->getResults());
        $usernames = array_map(function ($item) {
            return $item->getUser()->getUsername();
        }, $friends->getResults());
        self::assertContains('mutouyusei18', $usernames);
        self::assertContains('king_t_challa', $usernames);
        self::assertContains('johnyjohny', $usernames);

        // Second page
        $friends = $this->jikan->getUserFriends(new \Jikan\Request\User\UserFriendsRequest('morshuwarrior', 2));
        self::assertContainsOnlyInstancesOf(Friend::class, $friends->getResults());
        self::assertCount(100, $friends->getResults());
        $usernames = array_map(function ($item) {
            return $item->getUser()->getUsername();
        }, $friends->getResults());
        self::assertNotContains('mutouyusei18', $usernames);
        self::assertNotContains('king_t_challa', $usernames);
        self::assertNotContains('johnyjohny', $usernames);
        self::assertContains('MizzyMizuki', $usernames);

        // Empty page
        $friends = $this->jikan->getUserFriends(new \Jikan\Request\User\UserFriendsRequest('morshuwarrior', 100));
        self::assertCount(0, $friends->getResults());
    }

    /**
     * @test
     */
    public function it_gets_producer()
    {
        $producer = $this->jikan->getProducer(new \Jikan\Request\Producer\ProducerRequest(1));
        self::assertInstanceOf(\Jikan\Model\Producer\Producer::class, $producer);
    }

    /**
     * @test
     */
    public function it_gets_characters_and_staff()
    {
        $charactersAndStaff = $this->jikan->getAnimeCharactersAndStaff(
            new \Jikan\Request\Anime\AnimeCharactersAndStaffRequest(35073)
        );
        $staff = $charactersAndStaff->getStaff();
        $characters = $charactersAndStaff->getCharacters();
        self::assertCount(56, $characters);
        self::assertCount(26, $staff);
    }

    /**
     * @test
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
     */
    public function it_gets_anime_pictures()
    {
        $pictures = $this->jikan->getAnimePictures(new \Jikan\Request\Anime\AnimePicturesRequest(1));
        self::assertCount(13, $pictures);
    }

    /**
     * @test
     */
    public function it_gets_manga_pictures()
    {
        $pictures = $this->jikan->getMangaPictures(new \Jikan\Request\Manga\MangaPicturesRequest(1));
        self::assertCount(7, $pictures);
    }

    /**
     * @test
     */
    public function it_gets_person_pictures()
    {
        $pictures = $this->jikan->getPersonPictures(new \Jikan\Request\Person\PersonPicturesRequest(1));
        self::assertCount(7, $pictures);
    }

    /**
     * @test
     */
    public function it_gets_character_pictures()
    {
        $pictures = $this->jikan->getCharacterPictures(new \Jikan\Request\Character\CharacterPicturesRequest(1));
        self::assertCount(15, $pictures);
    }

    /**
     * @test
     */
    public function it_gets_manga_news()
    {
        $items = $this->jikan->getNewsList(new MangaNewsRequest(2))->getResults();
        self::assertCount(25, $items);
        self::assertContainsOnlyInstancesOf(NewsListItem::class, $items);
    }

    /**
     * @test
     */
    public function it_gets_anime_news()
    {
        $items = $this->jikan->getNewsList(new AnimeNewsRequest(21))->getResults();
        self::assertCount(30, $items);
        self::assertContainsOnlyInstancesOf(NewsListItem::class, $items);
    }

    /**
     * @test
     */
    public function it_gets_anime_search()
    {
        $search = $this->jikan->getAnimeSearch(new \Jikan\Request\Search\AnimeSearchRequest('Fate'));
        self::assertCount(50, $search->getResults());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Search\AnimeSearchListItem::class, $search->getResults());
        self::assertEquals(20, $search->getLastVisiblePage());
    }

    /**
     * @test
     */
    public function it_gets_manga_search()
    {
        $search = $this->jikan->getMangaSearch(new \Jikan\Request\Search\MangaSearchRequest('Fate'));
        self::assertCount(50, $search->getResults());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Search\MangaSearchListItem::class, $search->getResults());
        self::assertEquals(20, $search->getLastVisiblePage());
    }

    /**
     * @test
     */
    public function it_gets_character_search()
    {
        $search = $this->jikan->getCharacterSearch(new \Jikan\Request\Search\CharacterSearchRequest('Fate'));
        self::assertCount(50, $search->getResults());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Search\CharacterSearchListItem::class, $search->getResults());
        self::assertEquals(14, $search->getLastVisiblePage());
    }

    /**
     * @test
     */
    public function it_gets_person_search()
    {
        $search = $this->jikan->getPersonSearch(new \Jikan\Request\Search\PersonSearchRequest('Ara'));
        self::assertCount(50, $search->getResults());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Search\PersonSearchListItem::class, $search->getResults());
        self::assertEquals(20, $search->getLastVisiblePage());
    }

    /**
     * @test
     */
    public function it_gets_manga_characters()
    {
        $characters = $this->jikan->getMangaCharacters(new \Jikan\Request\Manga\MangaCharactersRequest(2));
        self::assertCount(74, $characters);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Manga\CharacterListItem::class, $characters);
    }

    /**
     * @test
     */
    public function it_gets_top_anime()
    {
        $anime = $this->jikan->getTopAnime(new \Jikan\Request\Top\TopAnimeRequest());
        self::assertCount(50, $anime->getResults());
        self::assertContainsOnlyInstancesOf(TopAnimeListItem::class, $anime->getResults());
        $titles = array_map(function ($item) {
            return $item->getTitle();
        }, $anime->getResults());
        self::assertContains('Fullmetal Alchemist: Brotherhood', $titles);
        self::assertContains('Steins;Gate', $titles);
    }

    /**
     * @test
     */
    public function it_gets_top_manga()
    {
        $manga = $this->jikan->getTopManga(new \Jikan\Request\Top\TopMangaRequest());
        self::assertCount(50, $manga->getResults());
        self::assertContainsOnlyInstancesOf(TopMangaListItem::class, $manga->getResults());
        $titles = array_map(function ($item) {
            return $item->getTitle();
        }, $manga->getResults());
        self::assertContains('Berserk', $titles);
        self::assertContains('One Piece', $titles);
    }

    /**
     * @test
     */
    public function it_gets_top_characters()
    {
        $characters = $this->jikan->getTopCharacters(new \Jikan\Request\Top\TopCharactersRequest());
        self::assertCount(50, $characters->getResults());
        self::assertContainsOnlyInstancesOf(TopCharacterListItem::class, $characters->getResults());
        $titles = array_map(function ($item) {
            return $item->getTitle();
        }, $characters->getResults());
        self::assertContains('Lamperouge, Lelouch', $titles);
        self::assertContains('Monkey D., Luffy', $titles);
    }

    /**
     * @test
     */
    public function it_gets_top_people()
    {
        $people = $this->jikan->getTopPeople(new \Jikan\Request\Top\TopPeopleRequest());
        self::assertCount(50, $people->getResults());
        self::assertContainsOnlyInstancesOf(TopPersonListItem::class, $people->getResults());
        $titles = array_map(function ($item) {
            return $item->getTitle();
        }, $people->getResults());
        self::assertContains('Hanazawa, Kana', $titles);
        self::assertContains('Asano, Inio', $titles);
    }

    /**
     * @test
     */
    public function it_gets_anime_forum()
    {
        $topics = $this->jikan->getAnimeForum(new AnimeForumRequest(21));
        self::assertCount(15, $topics);
        self::assertContainsOnlyInstancesOf(ForumTopic::class, $topics);
        $titles = array_map(function ($item) {
            return $item->getTitle();
        }, $topics);
        self::assertContains('My Top 5 Arcs, What Are Yours?', $titles);
        self::assertContains('One Piece Episode 28 Discussion', $titles);
    }

    /**
     * @test
     */
    public function it_gets_manga_forum()
    {
        $topics = $this->jikan->getMangaForum(new MangaForumRequest(21));
        self::assertCount(15, $topics);
        self::assertContainsOnlyInstancesOf(ForumTopic::class, $topics);
        $titles = array_map(function ($item) {
            return $item->getTitle();
        }, $topics);
        self::assertContains('Death Note Chapter 54 Discussion', $titles);
        self::assertContains('Death Note Chapter 91 Discussion', $titles);
    }

    /**
     * @test
     */
    public function it_gets_user_history()
    {
        $history = $this->jikan->getUserHistory(new \Jikan\Request\User\UserHistoryRequest('morshuwarrior'));
        self::assertCount(82, $history);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\User\History::class, $history);
    }

    /**
     * @test
     */
    public function it_gets_anime_episodes()
    {
        $episodes = $this->jikan->getAnimeEpisodes(new \Jikan\Request\Anime\AnimeEpisodesRequest(21));
        self::assertCount(100, $episodes->getResults());
        self::assertEquals(11, $episodes->getLastVisiblePage());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Anime\EpisodeListItem::class, $episodes->getResults());
    }

    /**
     * @test
     */
    public function it_gets_user_anime_list() // TODO: Add more test cases
    {
        $expectedStartDate = new DateTimeImmutable('2017-10-14');
        $expectedUserStartDate = new DateTimeImmutable('2017-10-14');
        $expectedEndDate = new DateTimeImmutable('2018-03-31');
        $expectedUserEndDate = new DateTimeImmutable('2018-03-31');

        $animeList = $this->jikan->getUserAnimeList(
            new \Jikan\Request\User\UserAnimeListRequest('ivanovishado', 1, Constants::USER_ANIME_LIST_COMPLETED)
        );

        foreach ($animeList as $anime) {
            $title = $anime->getTitle();
            if ($title === '3-gatsu no Lion 2nd Season') {
                self::assertEquals($expectedStartDate, $anime->getStartDate());
                self::assertEquals($expectedUserStartDate, $anime->getWatchStartDate());
                self::assertEquals($expectedEndDate, $anime->getEndDate());
                self::assertEquals($expectedUserEndDate, $anime->getWatchEndDate());
                return;
            }
        }
    }
}
