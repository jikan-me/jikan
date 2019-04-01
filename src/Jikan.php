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
use Jikan\Exception\ParserException;
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
     */
    public function __construct(GuzzleClient $guzzle = null)
    {
        $this->myanimelist = new MalClient($guzzle);
    }

    /**
     * @param int $id
     *
     * @return \Jikan\Model\Anime\Anime
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function Anime(int $id): Model\Anime\Anime
    {
        return $this->myanimelist->getAnime(
            new Request\Anime\AnimeRequest($id)
        );
    }

    /**
     * @param int $id
     *
     * @return \Jikan\Model\Anime\AnimeCharactersAndStaff
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function AnimeCharactersAndStaff(int $id): Model\Anime\AnimeCharactersAndStaff
    {
        return $this->myanimelist->getAnimeCharactersAndStaff(
            new Request\Anime\AnimeCharactersAndStaffRequest($id)
        );
    }

    /**
     * @param int $id
     * @param int $page
     *
     * @return \Jikan\Model\Anime\Episodes
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function AnimeEpisodes(int $id, int $page = 1): Model\Anime\Episodes
    {
        return $this->myanimelist->getAnimeEpisodes(
            new Request\Anime\AnimeEpisodesRequest($id, $page)
        );
    }

    /**
     * @param int $id
     *
     * @return \Jikan\Model\Anime\AnimeVideos
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function AnimeVideos(int $id): Model\Anime\AnimeVideos
    {
        return $this->myanimelist->getAnimeVideos(
            new Request\Anime\AnimeVideosRequest($id)
        );
    }

    /**
     * @param int $id
     *
     * @return \Jikan\Model\Common\Picture[]
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function AnimePictures(int $id): array
    {
        return $this->myanimelist->getAnimePictures(
            new Request\Anime\AnimePicturesRequest($id)
        );
    }


    /**
     * @param int $id
     *
     * @return Model\News\NewsListItem[]
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function AnimeNews(int $id): array
    {
        return $this->myanimelist->getNewsList(
            new Request\Anime\AnimeNewsRequest($id)
        );
    }

    /**
     * @param int $id
     *
     * @return Model\Forum\ForumTopic[]
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function AnimeForum(int $id): array
    {
        return $this->myanimelist->getAnimeForum(
            new Request\Anime\AnimeForumRequest($id)
        );
    }

    /**
     * @param int $id
     *
     * @return Model\Anime\AnimeStats
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function AnimeStats(int $id): Model\Anime\AnimeStats
    {
        return $this->myanimelist->getAnimeStats(
            new Request\Anime\AnimeStatsRequest($id)
        );
    }

    /**
     * @param int $id
     *
     * @return string|null
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function AnimeMoreInfo(int $id): ?string
    {
        return $this->myanimelist->getAnimeMoreInfo(
            new Request\Anime\AnimeMoreInfoRequest($id)
        );
    }

    /**
     * @param int $id
     *
     * @return \Jikan\Model\Manga\Manga
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function Manga(int $id): Model\Manga\Manga
    {
        return $this->myanimelist->getManga(
            new Request\Manga\MangaRequest($id)
        );
    }

    /**
     * @param int $id
     *
     * @return Model\Manga\CharacterListItem[]
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function MangaCharacters(int $id): array
    {
        return $this->myanimelist->getMangaCharacters(
            new Request\Manga\MangaCharactersRequest($id)
        );
    }

    /**
     * @param int $id
     *
     * @return \Jikan\Model\Common\Picture[]
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function MangaPictures(int $id): array
    {
        return $this->myanimelist->getMangaPictures(
            new Request\Manga\MangaPicturesRequest($id)
        );
    }

    /**
     * @param int $id
     *
     * @return Model\News\NewsListItem[]
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function MangaNews(int $id): array
    {
        return $this->myanimelist->getNewsList(
            new Request\Manga\MangaNewsRequest($id)
        );
    }

    /**
     * @param int $id
     *
     * @return Model\Forum\ForumTopic[]
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function MangaForum(int $id): array
    {
        return $this->myanimelist->getMangaForum(
            new Request\Manga\MangaForumRequest($id)
        );
    }

    /**
     * @param int $id
     *
     * @return Model\Manga\MangaStats
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function MangaStats(int $id): Model\Manga\MangaStats
    {
        return $this->myanimelist->getMangaStats(
            new Request\Manga\MangaStatsRequest($id)
        );
    }

    /**
     * @param int $id
     *
     * @return string|null
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function MangaMoreInfo(int $id): ?string
    {
        return $this->myanimelist->getMangaMoreInfo(
            new Request\Manga\MangaMoreInfoRequest($id)
        );
    }

    /**
     * @param int $id
     *
     * @return \Jikan\Model\Character\Character
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function Character(int $id): Model\Character\Character
    {
        return $this->myanimelist->getCharacter(
            new Request\Character\CharacterRequest($id)
        );
    }

    /**
     * @param int $id
     *
     * @return \Jikan\Model\Common\Picture[]
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function CharacterPictures(int $id): array
    {
        return $this->myanimelist->getCharacterPictures(
            new Request\Character\CharacterPicturesRequest($id)
        );
    }

    /**
     * @param int $id
     *
     * @return \Jikan\Model\Person\Person
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function Person(int $id): Model\Person\Person
    {
        return $this->myanimelist->getPerson(
            new Request\Person\PersonRequest($id)
        );
    }

    /**
     * @param int $id
     *
     * @return \Jikan\Model\Common\Picture[]
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function PersonPictures(int $id): array
    {
        return $this->myanimelist->getPersonPictures(
            new Request\Person\PersonPicturesRequest($id)
        );
    }

    /**
     * @param int|null $year
     * @param string|null $season
     * @return \Jikan\Model\Seasonal\Seasonal
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function Seasonal(?int $year = null, ?string $season = null): Model\Seasonal\Seasonal
    {
        return $this->myanimelist->getSeasonal(
            new Request\Seasonal\SeasonalRequest($year, $season)
        );
    }

    /**
     *
     * @return array
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function SeasonList(): array
    {
        return $this->myanimelist->getSeasonList(
            new Request\SeasonList\SeasonListRequest()
        );
    }

    /**
     *
     * @return \Jikan\Model\Schedule\Schedule
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function Schedule(): Model\Schedule\Schedule
    {
        return $this->myanimelist->getSchedule(
            new Request\Schedule\ScheduleRequest()
        );
    }

    /**
     * @param int $id
     * @param int $page
     *
     * @return \Jikan\Model\Producer\Producer
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function Producer(int $id, int $page = 1): Model\Producer\Producer
    {
        return $this->myanimelist->getProducer(
            new Request\Producer\ProducerRequest($id, $page)
        );
    }

    /**
     * @param int $id
     * @param int $page
     *
     * @return \Jikan\Model\Magazine\Magazine
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function Magazine(int $id, int $page = 1): Model\Magazine\Magazine
    {
        return $this->myanimelist->getMagazine(
            new Request\Magazine\MagazineRequest($id, $page)
        );
    }

    /**
     * @param int $id
     * @param int $page
     *
     * @return \Jikan\Model\Genre\AnimeGenre
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function AnimeGenre(int $id, int $page = 1): Model\Genre\AnimeGenre
    {
        return $this->myanimelist->getAnimeGenre(
            new Request\Genre\AnimeGenreRequest($id, $page)
        );
    }

    /**
     * @param int $id
     * @param int $page
     *
     * @return \Jikan\Model\Genre\MangaGenre
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function MangaGenre(int $id, int $page = 1): Model\Genre\MangaGenre
    {
        return $this->myanimelist->getMangaGenre(
            new Request\Genre\MangaGenreRequest($id, $page)
        );
    }

    /**
     * @param int $page
     * @param string|null $type
     *
     * @return Model\Top\TopAnime[]
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function TopAnime(int $page = 1, ?string $type = null): array
    {
        return $this->myanimelist->getTopAnime(
            new Request\Top\TopAnimeRequest($page, $type)
        );
    }

    /**
     * @param int $page
     * @param string|null $type
     *
     * @return Model\Top\TopManga[]
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function TopManga(int $page = 1, ?string $type = null): array
    {
        return $this->myanimelist->getTopManga(
            new Request\Top\TopMangaRequest($page, $type)
        );
    }

    /**
     * @param int $page
     *
     * @return Model\Top\TopCharacter[]
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function TopCharacters(int $page = 1): array
    {
        return $this->myanimelist->getTopCharacters(
            new Request\Top\TopCharactersRequest($page)
        );
    }

    /**
     * @param int $page
     *
     * @return Model\Top\TopPerson[]
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function TopPeople(int $page = 1): array
    {
        return $this->myanimelist->getTopPeople(
            new Request\Top\TopPeopleRequest($page)
        );
    }

    /**
     * @param string|null $query
     * @param int $page
     * @param null|Request\Search\AnimeSearchRequest $request
     *
     * @return Model\Search\AnimeSearch
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function AnimeSearch(
        ?string $query,
        int $page = 1,
        ?Request\Search\AnimeSearchRequest $request = null
    ): Model\Search\AnimeSearch {
        return $this->myanimelist->getAnimeSearch(
            null !== $request
                ? $request
                ->setQuery($query)
                ->setPage($page)
                : new Request\Search\AnimeSearchRequest($query, $page)
        );
    }

    /**
     * @param string|null $query
     * @param int $page
     * @param null|Request\Search\MangaSearchRequest $request
     *
     * @return Model\Search\MangaSearch
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function MangaSearch(
        ?string $query,
        int $page = 1,
        ?Request\Search\MangaSearchRequest $request = null
    ): Model\Search\MangaSearch {
        return $this->myanimelist->getMangaSearch(
            null !== $request
                ? $request
                ->setQuery($query)
                ->setPage($page)
                : new Request\Search\MangaSearchRequest($query, $page)
        );
    }

    /**
     * @param string|null $query
     * @param int $page
     * @param null|Request\Search\CharacterSearchRequest $request
     *
     * @return Model\Search\CharacterSearch
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function CharacterSearch(
        ?string $query,
        int $page = 1,
        ?Request\Search\CharacterSearchRequest $request = null
    ): Model\Search\CharacterSearch {
        return $this->myanimelist->getCharacterSearch(
            null !== $request
                ? $request
                ->setQuery($query)
                ->setPage($page)
                : new Request\Search\CharacterSearchRequest($query, $page)
        );
    }

    /**
     * @param string|null $query
     * @param int $page
     * @param Request\Search\PersonSearchRequest|Request\Search\PersonSearchRequest|null $request
     *
     * @return Model\Search\PersonSearch
     * @throws ParserException
     */
    public function PersonSearch(
        ?string $query,
        int $page = 1,
        ?Request\Search\PersonSearchRequest $request = null
    ): Model\Search\PersonSearch {
        return $this->myanimelist->getPersonSearch(
            null !== $request
                ? $request
                ->setQuery($query)
                ->setPage($page)
                : new Request\Search\PersonSearchRequest($query, $page)
        );
    }

    /**
     * @param string $username
     *
     * @return \Jikan\Model\User\Profile
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function UserProfile(string $username): Model\User\Profile
    {
        return $this->myanimelist->getUserProfile(
            new Request\User\UserProfileRequest($username)
        );
    }

    /**
     * @param string $username
     * @param int $page
     *
     * @return Model\User\Friend[]
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function UserFriends(string $username, int $page = 1): array
    {
        return $this->myanimelist->getUserFriends(
            new Request\User\UserFriendsRequest($username, $page)
        );
    }

    /**
     * @param string $username
     * @param string|null $type
     *
     * @return array
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function UserHistory(string $username, ?string $type = null): array
    {
        return $this->myanimelist->getUserHistory(
            new Request\User\UserHistoryRequest($username, $type)
        );
    }

    /**
     * @param string $username
     * @param int    $page
     * @param int    $status
     *
     * @return array
     * @throws ParserException
     */
    public function UserAnimeList(string $username, int $page = 1, int $status = 7): array
    {
        return $this->myanimelist->getUserAnimeList(
            new Request\User\UserAnimeListRequest($username, $page, $status)
        );
    }

    /**
     * @param string $username
     * @param int    $page
     * @param int    $status
     *
     * @return array
     * @throws ParserException
     */
    public function UserMangaList(string $username, int $page = 1, int $status = 7): array
    {
        return $this->myanimelist->getUserMangaList(
            new Request\User\UserMangaListRequest($username, $page, $status)
        );
    }


    /**
     * @param int $id
     * @param int $page
     *
     * @return array
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function AnimeRecentlyUpdatedByUsers(int $id, int $page = 1): array
    {
        return $this->myanimelist->getAnimeRecentlyUpdatedByUsers(
            new Request\Anime\AnimeRecentlyUpdatedByUsersRequest($id, $page)
        );
    }

    /**
     * @param int $id
     * @param int $page
     *
     * @return array
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function MangaRecentlyUpdatedByUsers(int $id, int $page = 1): array
    {
        return $this->myanimelist->getMangaRecentlyUpdatedByUsers(
            new Request\Manga\MangaRecentlyUpdatedByUsersRequest($id, $page)
        );
    }

    /**
     * @param int $id
     *
     * @return array
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function AnimeRecommendations(int $id): array
    {
        return $this->myanimelist->getAnimeRecommendations(
            new Request\Anime\AnimeRecommendationsRequest($id)
        );
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function MangaRecommendations(int $id): array
    {
        return $this->myanimelist->getMangaRecommendations(
            new Request\Manga\MangaRecommendationsRequest($id)
        );
    }

    /**
     * @param int $id
     * @param int $page
     *
     * @return array|Model\Club\UserProfile[]
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function ClubUsers(int $id, int $page = 1): array
    {
        return $this->myanimelist->getClubUsers(
            new Request\Club\UserListRequest($id, $page)
        );
    }

    /**
     * @param int $id
     * @param int $page
     * @return array
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function AnimeReviews(int $id, int $page): array
    {
        return $this->myanimelist->getAnimeReviews(
            new Request\Anime\AnimeReviewsRequest($id, $page)
        );
    }

    /**
     * @param int $id
     * @param int $page
     * @return array
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function MangaReviews(int $id, int $page): array
    {
        return $this->myanimelist->getMangaReviews(
            new Request\Manga\MangaReviewsRequest($id, $page)
        );
    }

    /**
     * @param int $id
     * @return Model\Club\Club
     * @throws Exception\BadResponseException
     * @throws ParserException
     */
    public function Club(int $id): Model\Club\Club
    {
        return $this->myanimelist->getClub(
            new Request\Club\ClubRequest($id)
        );
    }
}
