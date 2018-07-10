<?php
/**
 *    Jikan - MyAnimeList.net Unofficial API v2
 *
 *    This is an unofficial MAL API that stands in for the lackluster features of the official API.
 *    Jikan scrapes and parses the data you request from MAL. No authentication is needed for utilizing this library.
 *
 *    Jikan is NOT affiliated with MyAnimeList.net
 *    This library does not perform any rate limitations, so use it responsibly.
 */

namespace Jikan;

use GuzzleHttp\Client as GuzzleClient;
use Jikan\Model;
use Jikan\MyAnimeList\MalClient;
use Jikan\Request;

/**
 * Class Jikan
 *
 * @package Jikan
 */
class Jikan
{
    /**
     * @var MalClient
     */
    private $myanimelist;

    /**
     * Jikan constructor.
     *
     * @param GuzzleClient|null $guzzle
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(GuzzleClient $guzzle = null)
    {
        $this->myanimelist = new MalClient($guzzle);
    }

    /**
     * @param Request\Anime\AnimeRequest $request
     *
     * @return \Jikan\Model\Anime\Anime
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Anime(Request\Anime\AnimeRequest $request): Model\Anime\Anime
    {
        return $this->myanimelist->getAnime($request);
    }

    /**
     * @param Request\Anime\AnimeEpisodesRequest $request
     *
     * @return \Jikan\Model\Anime\Episodes
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function AnimeEpisodes(Request\Anime\AnimeEpisodesRequest $request): Model\Anime\Episodes
    {
        return $this->myanimelist->getAnimeEpisodes($request);
    }

    /**
     * @param Request\Anime\AnimeVideosRequest $request
     *
     * @return \Jikan\Model\Anime\AnimeVideos
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function AnimeVideos(Request\Anime\AnimeVideosRequest $request): Model\Anime\AnimeVideos
    {
        return $this->myanimelist->getAnimeVideos($request);
    }

    /**
     * @param Request\Manga\MangaRequest $request
     *
     * @return \Jikan\Model\Manga\Manga
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Manga(Request\Manga\MangaRequest $request): Model\Manga\Manga
    {
        return $this->myanimelist->getManga($request);
    }

    /**
     * @param Request\Character\CharacterRequest $request
     *
     * @return \Jikan\Model\Character\Character
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Character(Request\Character\CharacterRequest $request): Model\Character\Character
    {
        return $this->myanimelist->getCharacter($request);
    }

    /**
     * @param Request\Anime\AnimeCharactersAndStaffRequest $request
     *
     * @return \Jikan\Model\Anime\CharactersAndStaff
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function CharactersAndStaff(Request\Anime\AnimeCharactersAndStaffRequest $request): Model\Anime\CharactersAndStaff
    {
        return $this->myanimelist->getCharactersAndStaff($request);
    }

    /**
     * @param Request\Person\PersonRequest $request
     *
     * @return \Jikan\Model\Person\Person
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Person(Request\Person\PersonRequest $request): Model\Person\Person
    {
        return $this->myanimelist->getPerson($request);
    }

    /**
     * @param Request\User\UserProfileRequest $request
     *
     * @return \Jikan\Model\User\UserProfile
     * @throws \InvalidArgumentException
     */
    public function UserProfile(Request\User\UserProfileRequest $request): Model\User\UserProfile
    {
        return $this->myanimelist->getUserProfile($request);
    }

    /**
     * @param Request\Seasonal\SeasonalRequest $request
     *
     * @return \Jikan\Model\Seasonal\Seasonal
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Seasonal(Request\Seasonal\SeasonalRequest $request): Model\Seasonal\Seasonal
    {
        return $this->myanimelist->getSeasonal($request);
    }

    /**
     * @param Request\Producer\ProducerRequest $request
     *
     * @return \Jikan\Model\Producer\Producer
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Producer(Request\Producer\ProducerRequest $request): Model\Producer\Producer
    {
        return $this->myanimelist->getProducer($request);
    }

    /**
     * @param Request\Magazine\MagazineRequest $request
     *
     * @return \Jikan\Model\Magazine\Magazine
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Magazine(Request\Magazine\MagazineRequest $request): Model\Magazine\Magazine
    {
        return $this->myanimelist->getMagazine($request);
    }


    /**
     * @param Request\Genre\AnimeGenreRequest $request
     *
     * @return \Jikan\Model\Anime\AnimeGenre
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function AnimeGenre(Request\Genre\AnimeGenreRequest $request): Model\Anime\AnimeGenre
    {
        return $this->myanimelist->getAnimeGenre($request);
    }

    /**
     * @param Request\Genre\MangaGenreRequest $request
     *
     * @return \Jikan\Model\Genre\MangaGenre
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function MangaGenre(Request\Genre\MangaGenreRequest $request): Model\Genre\MangaGenre
    {
        return $this->myanimelist->getMangaGenre($request);
    }

    /**
     * @param Request\Schedule\ScheduleRequest $request
     *
     * @return \Jikan\Model\Shedule\Schedule
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Schedule(Request\Schedule\ScheduleRequest $request): Model\Shedule\Schedule
    {
        return $this->myanimelist->getSchedule($request);
    }

    /**
     * @param Request\User\UserFriendsRequest $request
     *
     * @return Model\User\Friend[]
     * @throws \InvalidArgumentException
     */
    public function UserFriends(Request\User\UserFriendsRequest $request): array
    {
        return $this->myanimelist->getUserFriends($request);
    }

    /**
     * @param Request\Anime\AnimePicturesRequest $request
     *
     * @return \Jikan\Model\Common\Picture[]
     * @throws \InvalidArgumentException
     */
    public function AnimePictures(Request\Anime\AnimePicturesRequest $request): array
    {
        return $this->myanimelist->getAnimePictures($request);
    }

    /**
     * @param Request\Manga\MangaPicturesRequest $request
     *
     * @return \Jikan\Model\Common\Picture[]
     * @throws \InvalidArgumentException
     */
    public function MangaPictures(Request\Manga\MangaPicturesRequest $request): array
    {
        return $this->myanimelist->getMangaPictures($request);
    }

    /**
     * @param Request\Person\PersonPicturesRequest $request
     *
     * @return \Jikan\Model\Common\Picture[]
     * @throws \InvalidArgumentException
     */
    public function PersonPictures(Request\Person\PersonPicturesRequest $request): array
    {
        return $this->myanimelist->getPersonPictures($request);
    }

    /**
     * @param Request\Character\CharacterPicturesRequest $request
     *
     * @return \Jikan\Model\Common\Picture[]
     * @throws \InvalidArgumentException
     */
    public function CharacterPictures(Request\Character\CharacterPicturesRequest $request): array
    {
        return $this->myanimelist->getCharacterPictures($request);
    }

    /**
     * @param Request\News\AnimeNewsListRequest $request
     *
     * @return Model\News\NewsListItem[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function AnimeNewsList(Request\News\AnimeNewsListRequest $request): array
    {
        return $this->myanimelist->getNewsList($request);
    }

    /**
     * @param Request\News\MangaNewsListRequest $request
     *
     * @return Model\News\NewsListItem[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function MangaNewsList(Request\News\MangaNewsListRequest $request): array
    {
        return $this->myanimelist->getNewsList($request);
    }

    /**
     * @param Request\Search\AnimeSearchRequest $request
     *
     * @return Model\Search\AnimeSearch
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function AnimeSearch(Request\Search\AnimeSearchRequest $request): Model\Search\AnimeSearch
    {
        return $this->myanimelist->getAnimeSearch($request);
    }

    /**
     * @param Request\Search\MangaSearchRequest $request
     *
     * @return Model\Search\MangaSearch
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function MangaSearch(Request\Search\MangaSearchRequest $request): Model\Search\MangaSearch
    {
        return $this->myanimelist->getMangaSearch($request);
    }

    /**
     * @param Request\Search\CharacterSearchRequest $request
     *
     * @return Model\Search\CharacterSearch
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function CharacterSearch(Request\Search\CharacterSearchRequest $request): Model\Search\CharacterSearch
    {
        return $this->myanimelist->getCharacterSearch($request);
    }


    /**
     * @param Request\Search\PersonSearchRequest $request
     *
     * @return Model\Search\PersonSearchListItem[]
     */
    public function PersonSearch(Request\Search\PersonSearchRequest $request): array
    {
        return $this->myanimelist->getPersonSearch($request);
    }

    /**
     * @param Request\Manga\MangaCharactersRequest $request
     *
     * @return Model\Manga\CharacterListItem[]
     * @throws \InvalidArgumentException
     */
    public function MangaCharacters(Request\Manga\MangaCharactersRequest $request): array
    {
        return $this->myanimelist->getMangaCharacters($request);
    }

    /**
     * @param Request\Top\TopAnimeRequest|null $request
     *
     * @return Model\Top\TopAnime[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function TopAnime(?Request\Top\TopAnimeRequest $request = null): array
    {
        return $this->myanimelist->getTopAnime($request ?? new Request\Top\TopAnimeRequest());
    }

    /**
     * @param Request\Top\TopMangaRequest|null $request
     *
     * @return Model\Top\TopManga[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function TopManga(?Request\Top\TopMangaRequest $request = null): array
    {
        return $this->myanimelist->getTopManga($request ?? new Request\Top\TopMangaRequest());
    }

    /**
     * @param Request\Top\TopCharactersRequest|null $request
     *
     * @return Model\Top\TopCharacter[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function TopCharacters(?Request\Top\TopCharactersRequest $request = null): array
    {
        return $this->myanimelist->getTopCharacters($request ?? new Request\Top\TopCharactersRequest());
    }

    /**
     * @param Request\Top\TopPeopleRequest|null $request
     *
     * @return Model\Top\TopPeople[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function TopPeople(?Request\Top\TopPeopleRequest $request = null): array
    {
        return $this->myanimelist->getTopPeople($request ?? new Request\Top\TopPeopleRequest());
    }

    /**
     * @param Request\Anime\AnimeStatsRequest $request
     *
     * @return Model\Anime\AnimeStats
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function AnimeStats(Request\Anime\AnimeStatsRequest $request): Model\Anime\AnimeStats
    {
        return $this->myanimelist->getAnimeStats($request);
    }

    /**
     * @param Request\Manga\MangaStatsRequest $request
     *
     * @return Model\Manga\MangaStats
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function MangaStats(Request\Manga\MangaStatsRequest $request): Model\Manga\MangaStats
    {
        return $this->myanimelist->getMangaStats($request);
    }

    /**
     * @param Request\Forum\AnimeForumRequest $request
     *
     * @return Model\Forum\ForumTopic[]
     * @throws \InvalidArgumentException
     */
    public function AnimeForum(Request\Forum\AnimeForumRequest $request): array
    {
        return $this->myanimelist->getAnimeForumTopics($request);
    }

    /**
     * @param Request\Forum\MangaForumRequest $request
     *
     * @return Model\Forum\ForumTopic[]
     * @throws \InvalidArgumentException
     */
    public function MangaForum(Request\Forum\MangaForumRequest $request): array
    {
        return $this->myanimelist->getMangaForumTopics($request);
    }

    /**
     * @param Request\Anime\AnimeMoreInfoRequest $request
     *
     * @return Model\Anime\MoreInfo
     * @throws \InvalidArgumentException
     */
    public function AnimeMoreInfo(Request\Anime\AnimeMoreInfoRequest $request): Model\Anime\MoreInfo
    {
        return $this->myanimelist->getAnimeMoreInfo($request);
    }
}
