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
     */
    public function __construct(GuzzleClient $guzzle = null)
    {
        $this->myanimelist = new MalClient($guzzle);
    }

    /**
     * @param Request\Anime\AnimeRequest $request
     *
     * @return Model\Anime
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Anime(Request\Anime\AnimeRequest $request): Model\Anime
    {
        return $this->myanimelist->getAnime($request);
    }

    /**
     * @param Request\Anime\AnimeEpisodesRequest $request
     *
     * @return Model\Episodes
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function AnimeEpisodes(Request\Anime\AnimeEpisodesRequest $request): Model\Episodes
    {
        return $this->myanimelist->getAnimeEpisodes($request);
    }

    /**
     * @param Request\Anime\AnimeVideosRequest $request
     *
     * @return Model\AnimeVideos
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function AnimeVideos(Request\Anime\AnimeVideosRequest $request): Model\AnimeVideos
    {
        return $this->myanimelist->getAnimeVideos($request);
    }

    /**
     * @param Request\Manga\MangaRequest $request
     *
     * @return Model\Manga
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Manga(Request\Manga\MangaRequest $request): Model\Manga
    {
        return $this->myanimelist->getManga($request);
    }

    /**
     * @param Request\Character\CharacterRequest $request
     *
     * @return Model\Character
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Character(Request\Character\CharacterRequest $request): Model\Character
    {
        return $this->myanimelist->getCharacter($request);
    }

    /**
     * @param Request\Anime\AnimeCharactersAndStaff $request
     *
     * @return Model\CharactersAndStaff
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function CharactersAndStaff(Request\Anime\AnimeCharactersAndStaffRequest $request): Model\CharactersAndStaff
    {
        return $this->myanimelist->getCharactersAndStaff($request);
    }

    /**
     * @param Request\Person\PersonRequest $request
     *
     * @return Model\Person
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Person(Request\Person\PersonRequest $request): Model\Person
    {
        return $this->myanimelist->getPerson($request);
    }

    /**
     * @param Request\User\UserProfileRequest $request
     *
     * @return Model\UserProfile
     * @throws \InvalidArgumentException
     */
    public function UserProfile(Request\User\UserProfileRequest $request): Model\UserProfile
    {
        return $this->myanimelist->getUserProfile($request);
    }

    /**
     * @param Request\Seasonal\SeasonalRequest $request
     *
     * @return Model\Seasonal
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Seasonal(Request\Seasonal\SeasonalRequest $request): Model\Seasonal
    {
        return $this->myanimelist->getSeasonal($request);
    }

    /**
     * @param Request\Producer\ProducerRequest $request
     *
     * @return Model\Producer
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Producer(Request\Producer\ProducerRequest $request): Model\Producer
    {
        return $this->myanimelist->getProducer($request);
    }

    /**
     * @param Request\Magazine\MagazineRequest $request
     *
     * @return Model\Magazine
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Magazine(Request\Magazine\MagazineRequest $request): Model\Magazine
    {
        return $this->myanimelist->getMagazine($request);
    }


    /**
     * @param Request\Genre\AnimeGenre $request
     *
     * @return Model\AnimeGenre
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function AnimeGenre(Request\Genre\AnimeGenre $request): Model\AnimeGenre
    {
        return $this->myanimelist->getAnimeGenre($request);
    }

    /**
     * @param Request\Genre\MangaGenre $request
     *
     * @return Model\MangaGenre
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function MangaGenre(Request\Genre\MangaGenre $request): Model\MangaGenre
    {
        return $this->myanimelist->getMangaGenre($request);
    }

    /**
     * @param Request\Schedule\ScheduleRequest $request
     *
     * @return Model\Schedule
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Schedule(Request\Schedule\ScheduleRequest $request): Model\Schedule
    {
        return $this->myanimelist->getSchedule($request);
    }

    /**
     * @param Request\User\UserFriendsRequest $request
     *
     * @return Model\UserFriend[]
     * @throws \InvalidArgumentException
     */
    public function UserFriends(Request\User\UserFriendsRequest $request): array
    {
        return $this->myanimelist->getUserFriends($request);
    }

    /**
     * @param Request\Anime\AnimePicturesRequest $request
     *
     * @return Model\Picture[]
     */
    public function AnimePictures(Request\Anime\AnimePicturesRequest $request): array
    {
        return $this->myanimelist->getAnimePictures($request);
    }

    /**
     * @param Request\Manga\MangaPicturesRequest $request
     *
     * @return Model\Picture[]
     */
    public function MangaPictures(Request\Manga\MangaPicturesRequest $request): array
    {
        return $this->myanimelist->getMangaPictures($request);
    }

    /**
     * @param Request\Person\PersonPicturesRequest $request
     *
     * @return Model\Picture[]
     */
    public function PersonPictures(Request\Person\PersonPicturesRequest $request): array
    {
        return $this->myanimelist->getPersonPictures($request);
    }

    /**
     * @param Request\Character\CharacterPicturesRequest $request
     *
     * @return Model\Picture[]
     */
    public function CharacterPictures(Request\Character\CharacterPicturesRequest $request): array
    {
        return $this->myanimelist->getCharacterPictures($request);
    }

    /**
     * @param Request\News\AnimeNewsListRequest $request
     *
     * @return Model\News\NewsListItem[]
     */
    public function AnimeNewsList(Request\News\AnimeNewsListRequest $request): array
    {
        return $this->myanimelist->getNewsList($request);
    }

    /**
     * @param Request\News\MangaNewsListRequest $request
     *
     * @return Model\News\NewsListItem[]
     */
    public function MangaNewsList(Request\News\MangaNewsListRequest $request): array
    {
        return $this->myanimelist->getNewsList($request);
    }

    /**
     * @param Request\Search\AnimeSearchRequest $request
     *
     * @return Model\Search\AnimeSearch
     */
    public function AnimeSearch(Request\Search\AnimeSearchRequest $request): Model\Search\AnimeSearch
    {
        return $this->myanimelist->getAnimeSearch($request);
    }

    /**
     * @param Request\Search\MangaSearchRequest $request
     *
     * @return Model\Search\MangaSearch
     */
    public function MangaSearch(Request\Search\MangaSearchRequest $request): Model\Search\MangaSearch
    {
        return $this->myanimelist->getMangaSearch($request);
    }

    /**
     * @param Request\Search\CharacterSearchRequest $request
     *
     * @return Model\Search\CharacterSearch
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

    public function MangaCharacters(Request\Manga\MangaCharactersRequest $request)
    {
        return $this->myanimelist->getMangaCharacters($request);
    }
}
