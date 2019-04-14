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

namespace Jikan\MyAnimeList;

use GuzzleHttp\Client as GuzzleClient;
use Jikan\Exception\BadResponseException;
use Jikan\Exception\ParserException;
use Jikan\Goutte\GoutteWrapper;
use Jikan\Model;
use Jikan\Parser;
use Jikan\Request;

/**
 * Class MalClient
 */
class MalClient
{
    /**
     * @var GoutteWrapper
     */
    private $ghoutte;

    /**
     * MalClient constructor.
     *
     * @param GuzzleClient|null $guzzle
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(GuzzleClient $guzzle = null)
    {
        $this->ghoutte = new GoutteWrapper();
        if ($guzzle !== null) {
            $this->ghoutte->setClient($guzzle);
        }
    }

    /**
     * @param Request\Anime\AnimeRequest $request
     * @return Model\Anime\Anime
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnime(Request\Anime\AnimeRequest $request): Model\Anime\Anime
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Anime\AnimeParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Anime\AnimeEpisodesRequest $request
     * @return Model\Anime\Episodes
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeEpisodes(Request\Anime\AnimeEpisodesRequest $request): Model\Anime\Episodes
    {
        // Episode page returns 404 when there are no results
        try {
            $crawler = $this->ghoutte->request('GET', $request->getPath());
        } catch (\Exception $e) {
            if ($e->getCode() === 404) {
                return new Model\Anime\Episodes();
            }

            throw $e;
        }

        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Anime\EpisodesParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Anime\AnimeVideosRequest $request
     * @return Model\Anime\AnimeVideos
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeVideos(Request\Anime\AnimeVideosRequest $request): Model\Anime\AnimeVideos
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Anime\VideosParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Manga\MangaRequest $request
     * @return Model\Manga\Manga
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getManga(Request\Manga\MangaRequest $request): Model\Manga\Manga
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Manga\MangaParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }


    /**
     * @param Request\Character\CharacterRequest $request
     * @return Model\Character\Character
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getCharacter(Request\Character\CharacterRequest $request): Model\Character\Character
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());

        // MAL returns `Invalid ID provided.` instead of 404 on invalid characters
        $badResult = $crawler->filterXPath('//*[@id="content"]/div[@class="badresult"]');

        if ($badResult->count()) {
            throw new BadResponseException(sprintf('404 on %s', $request->getPath()), 404);
        }

        try {
            $parser = new Parser\Character\CharacterParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Person\PersonRequest $request
     * @return Model\Person\Person
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getPerson(Request\Person\PersonRequest $request): Model\Person\Person
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Person\PersonParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\User\UserProfileRequest $request
     * @return Model\User\Profile
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getUserProfile(Request\User\UserProfileRequest $request): Model\User\Profile
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\User\Profile\UserProfileParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\User\UserFriendsRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getUserFriends(Request\User\UserFriendsRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\User\Friends\FriendsParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Seasonal\SeasonalRequest $request
     * @return Model\Seasonal\Seasonal
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getSeasonal(Request\Seasonal\SeasonalRequest $request): Model\Seasonal\Seasonal
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Seasonal\SeasonalParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Producer\ProducerRequest $request
     * @return Model\Producer\Producer
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getProducer(Request\Producer\ProducerRequest $request): Model\Producer\Producer
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Producer\ProducerParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Magazine\MagazineRequest $request
     * @return Model\Magazine\Magazine
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMagazine(Request\Magazine\MagazineRequest $request): Model\Magazine\Magazine
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Magazine\MagazineParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Genre\AnimeGenreRequest $request
     * @return Model\Genre\AnimeGenre
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeGenre(Request\Genre\AnimeGenreRequest $request): Model\Genre\AnimeGenre
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Genre\AnimeGenreParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Genre\MangaGenreRequest $request
     * @return Model\Genre\MangaGenre
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaGenre(Request\Genre\MangaGenreRequest $request): Model\Genre\MangaGenre
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Genre\MangaGenreParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Schedule\ScheduleRequest $request
     * @return Model\Schedule\Schedule
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getSchedule(Request\Schedule\ScheduleRequest $request): Model\Schedule\Schedule
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Schedule\ScheduleParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Anime\AnimeCharactersAndStaffRequest $request
     * @return Model\Anime\AnimeCharactersAndStaff
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeCharactersAndStaff(
        Request\Anime\AnimeCharactersAndStaffRequest $request
    ): Model\Anime\AnimeCharactersAndStaff {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Anime\CharactersAndStaffParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Anime\AnimePicturesRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimePictures(Request\Anime\AnimePicturesRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Common\PicturesPageParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Manga\MangaPicturesRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaPictures(Request\Manga\MangaPicturesRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Common\PicturesPageParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Character\CharacterPicturesRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getCharacterPictures(Request\Character\CharacterPicturesRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Common\PicturesPageParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Person\PersonPicturesRequest $request
     *
     * @return Model\Common\Picture[]
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getPersonPictures(Request\Person\PersonPicturesRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Common\PicturesPageParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\RequestInterface $request
     *
     * @return Model\News\NewsListItem[]
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getNewsList(Request\RequestInterface $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\News\NewsListParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Search\AnimeSearchRequest $request
     *
     * @return Model\Search\AnimeSearch
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeSearch(Request\Search\AnimeSearchRequest $request): Model\Search\AnimeSearch
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Search\AnimeSearchParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Search\MangaSearchRequest $request
     *
     * @return Model\Search\MangaSearch
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaSearch(Request\Search\MangaSearchRequest $request): Model\Search\MangaSearch
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Search\MangaSearchParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Search\CharacterSearchRequest $request
     *
     * @return Model\Search\CharacterSearch
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getCharacterSearch(Request\Search\CharacterSearchRequest $request): Model\Search\CharacterSearch
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Search\CharacterSearchParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Search\PersonSearchRequest $request
     *
     * @return Model\Search\PersonSearch
     * @throws ParserException
     */
    public function getPersonSearch(Request\Search\PersonSearchRequest $request): Model\Search\PersonSearch
    {
        // Person page for some reason returns 404 when there are no results :facepalm:
        try {
            $crawler = $this->ghoutte->request('GET', $request->getPath());
        } catch (\Exception $e) {
            if ($e->getCode() === 404) {
                return new Model\Search\PersonSearch();
            }

            throw $e;
        }

        try {
            $parser = new Parser\Search\PersonSearchParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Manga\MangaCharactersRequest $request
     *
     * @return Model\Manga\CharacterListItem[]
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaCharacters(Request\Manga\MangaCharactersRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Manga\CharactersParser($crawler);

            return $parser->getCharacters();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Top\TopAnimeRequest $request
     *
     * @return Model\Top\TopAnime[]
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getTopAnime(Request\Top\TopAnimeRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Top\TopAnimeParser($crawler);

            return $parser->getTopAnime();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Top\TopMangaRequest $request
     *
     * @return Model\Top\TopManga[]
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getTopManga(Request\Top\TopMangaRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Top\TopMangaParser($crawler);

            return $parser->getTopManga();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Top\TopCharactersRequest $request
     *
     * @return Model\Top\TopCharacter[]
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getTopCharacters(Request\Top\TopCharactersRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Top\TopCharactersParser($crawler);

            return $parser->getTopCharacters();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Top\TopPeopleRequest $request
     *
     * @return Model\Top\TopPerson[]
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getTopPeople(Request\Top\TopPeopleRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Top\TopPeopleParser($crawler);

            return $parser->getTopPeople();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Anime\AnimeStatsRequest $request
     *
     * @return Model\Anime\AnimeStats
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeStats(Request\Anime\AnimeStatsRequest $request): Model\Anime\AnimeStats
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Anime\AnimeStatsParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Manga\MangaStatsRequest $request
     * @return Model\Manga\MangaStats
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaStats(Request\Manga\MangaStatsRequest $request): Model\Manga\MangaStats
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Manga\MangaStatsParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Anime\AnimeForumRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeForum(Request\Anime\AnimeForumRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Forum\ForumPageParser($crawler);

            return $parser->getTopics();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Manga\MangaForumRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaForum(Request\Manga\MangaForumRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Forum\ForumPageParser($crawler);

            return $parser->getTopics();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Anime\AnimeMoreInfoRequest $request
     * @return string|null
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeMoreInfo(Request\Anime\AnimeMoreInfoRequest $request): ?string
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Anime\MoreInfoParser($crawler);

            return $parser->getModel()->getMoreInfo();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Manga\MangaMoreInfoRequest $request
     * @return string|null
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaMoreInfo(Request\Manga\MangaMoreInfoRequest $request): ?string
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Manga\MoreInfoParser($crawler);

            return $parser->getModel()->getMoreInfo();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\SeasonList\SeasonListRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getSeasonList(Request\SeasonList\SeasonListRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\SeasonList\SeasonListParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\User\UserHistoryRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getUserHistory(Request\User\UserHistoryRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\User\History\HistoryParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\User\UserAnimeListRequest $request
     *
     * @return Model\User\AnimeListItem[]
     * @throws ParserException
     */
    public function getUserAnimeList(Request\User\UserAnimeListRequest $request): array
    {
        try {
            $response = $this->ghoutte->getClient()->get($request->getPath());
            $list = json_decode($response->getBody()->getContents());

            $model = [];
            foreach ($list as $item) {
                $model[] = Model\User\AnimeListItem::factory($item);
            }
            return $model;
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\User\UserMangaListRequest $request
     * @return array
     * @throws ParserException
     */
    public function getUserMangaList(Request\User\UserMangaListRequest $request): array
    {
        try {
            $response = $this->ghoutte->getClient()->get($request->getPath());
            $list = json_decode($response->getBody()->getContents());

            $model = [];
            foreach ($list as $item) {
                $model[] = Model\User\MangaListItem::factory($item);
            }
            return $model;
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }


    /**
     * @param Request\Anime\AnimeRecentlyUpdatedByUsersRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeRecentlyUpdatedByUsers(Request\Anime\AnimeRecentlyUpdatedByUsersRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Anime\AnimeRecentlyUpdatedByUsersParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Manga\MangaRecentlyUpdatedByUsersRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaRecentlyUpdatedByUsers(Request\Manga\MangaRecentlyUpdatedByUsersRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Manga\MangaRecentlyUpdatedByUsersParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Anime\AnimeRecommendationsRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeRecommendations(Request\Anime\AnimeRecommendationsRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Common\Recommendations($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Manga\MangaRecommendationsRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaRecommendations(Request\Manga\MangaRecommendationsRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());

        try {
            $parser = new Parser\Common\Recommendations($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Club\UserListRequest $request
     *
     * @return Model\Club\UserProfile[]
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getClubUsers(Request\Club\UserListRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());

        try {
            $parser = new Parser\Club\UserListParser($crawler);

            return $parser->getResults();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Anime\AnimeReviewsRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeReviews(Request\Anime\AnimeReviewsRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Anime\AnimeReviewsParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Manga\MangaReviewsRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaReviews(Request\Manga\MangaReviewsRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());

        try {
            $parser = new Parser\Manga\MangaReviewsParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Club\ClubRequest $request
     * @return Model\Club\Club
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getClub(Request\Club\ClubRequest $request): Model\Club\Club
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());

        try {
            $parser = new Parser\Club\ClubParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }
}
