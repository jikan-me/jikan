<?php
/**
 *    Jikan - MyAnimeList.net Unofficial API v3
 *
 *    This is an unofficial MAL API that stands in for the lackluster features of the official API.
 *    Jikan scrapes and parses the data you request from MAL. No authentication is needed for utilizing this library.
 *
 *    Jikan is NOT affiliated with MyAnimeList.net
 *    This library does not perform any rate limitations or caching, so use it responsibly.
 */

namespace Jikan\MyAnimeList;

use Jikan\Exception\BadResponseException;
use Jikan\Exception\ParserException;
use Jikan\Goutte\GoutteWrapper;
use Jikan\Model;
use Jikan\Parser;
use Jikan\Request;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class MalClient
 */
class MalClient
{
    /**
     * @var GoutteWrapper
     */
    protected $ghoutte;

    /**
     * @var HttpClientInterface|HttpClientInterface
     */
    protected $httpClient;

    /**
     * MalClient constructor.
     *
     * @param HttpClientInterface|null $httpClient
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(HttpClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?? HttpClient::create();
        $this->ghoutte = new GoutteWrapper($this->httpClient);
    }

    /**
     * @param  Request\Anime\AnimeRequest $request
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
     * @param  Request\Anime\AnimeEpisodesRequest $request
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

        try {
            $parser = new Parser\Anime\EpisodesParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param  Request\Anime\AnimeVideosRequest $request
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
     * @param Request\Anime\AnimeVideosEpisodesRequest $request
     * @return Model\Anime\AnimeVideosEpisodes
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeVideosEpisodes(Request\Anime\AnimeVideosEpisodesRequest $request): Model\Anime\AnimeVideosEpisodes
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Anime\VideosParser($crawler);

            return $parser->getResultsModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param  Request\Manga\MangaRequest $request
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
     * @param  Request\Character\CharacterRequest $request
     * @return Model\Character\Character
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getCharacter(Request\Character\CharacterRequest $request): Model\Character\Character
    {
        if ($request->getId() === 0) {
            throw new BadResponseException(sprintf('404 on %s', $request->getPath()), 404);
        }

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
     * @param  Request\Person\PersonRequest $request
     * @return Model\Person\Person
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getPerson(Request\Person\PersonRequest $request): Model\Person\Person
    {
        if ($request->getId() === 0) {
            throw new BadResponseException(sprintf('404 on %s', $request->getPath()), 404);
        }

        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Person\PersonParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param  Request\User\UserProfileRequest $request
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
     * @return Model\User\Friends
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getUserFriends(Request\User\UserFriendsRequest $request): Model\User\Friends
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
     * @param  Request\Seasonal\SeasonalRequest $request
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
     * @param  Request\Producer\ProducerRequest $request
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
     * @param  Request\Magazine\MagazineRequest $request
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
     * @param  Request\Genre\AnimeGenreRequest $request
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
     * @param  Request\Genre\MangaGenreRequest $request
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
     * @param  Request\Schedule\ScheduleRequest $request
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
     * @param  Request\Anime\AnimeCharactersAndStaffRequest $request
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
     * @param  Request\Anime\AnimePicturesRequest $request
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
     * @param  Request\Manga\MangaPicturesRequest $request
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
     * @param  Request\Character\CharacterPicturesRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getCharacterPictures(Request\Character\CharacterPicturesRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Common\DefaultPicturesPageParser($crawler);

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
            $parser = new Parser\Common\DefaultPicturesPageParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\RequestInterface $request
     *
     * @return Model\News\NewsList
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getNewsList(Request\RequestInterface $request): Model\News\NewsList
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

//        if ($this->ghoutte->getInternalResponse()->getStatusCode()) {
//            return Model\Search\AnimeSearch::mock();
//        }

        try {
            $parser = new Parser\Search\AnimeSearchParser($crawler);

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
    public function getAnimeSearchAlt(Request\Search\AnimeSearchRequest $request): Model\Search\AnimeSearchAlt
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
        try {
            $crawler = $this->ghoutte->request('GET', $request->getPath());
        } catch (BadResponseException $e) {
            if ($e->getCode() === 404) {
                return Model\Search\CharacterSearch::mock();
            }
        }

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
        try {
            $crawler = $this->ghoutte->request('GET', $request->getPath());
        } catch (BadResponseException $e) {
            if ($e->getCode() === 404) {
                return Model\Search\PersonSearch::mock();
            }
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
     * @return Model\Top\TopAnimeListItem[]
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getTopAnime(Request\Top\TopAnimeRequest $request): Model\Top\TopAnime
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Top\TopAnimeParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Top\TopMangaRequest $request
     *
     * @return Model\Top\TopMangaListItem[]
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getTopManga(Request\Top\TopMangaRequest $request): Model\Top\TopManga
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Top\TopMangaParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Top\TopCharactersRequest $request
     *
     * @return Model\Top\TopCharacterListItem[]
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getTopCharacters(Request\Top\TopCharactersRequest $request): Model\Top\TopCharacters
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Top\TopCharactersParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Top\TopPeopleRequest $request
     *
     * @return Model\Top\TopPersonListItem[]
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getTopPeople(Request\Top\TopPeopleRequest $request): Model\Top\TopPeople
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Top\TopPeopleParser($crawler);

            return $parser->getModel();
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
     * @param  Request\Manga\MangaStatsRequest $request
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
     * @param  Request\Anime\AnimeForumRequest $request
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
     * @param  Request\Manga\MangaForumRequest $request
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
     * @param  Request\Anime\AnimeMoreInfoRequest $request
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
     * @param  Request\Manga\MangaMoreInfoRequest $request
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
     * @param  Request\SeasonList\SeasonListRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getSeasonList(Request\SeasonList\SeasonListRequest $request): Model\SeasonList\SeasonArchive
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
     * @param  Request\User\UserHistoryRequest $request
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
     * @param  Request\User\UserAnimeListRequest $request
     * @return array
     * @throws BadResponseException
     */
    public function getUserAnimeList(Request\User\UserAnimeListRequest $request): array
    {
        try {
            $response = $this->httpClient
                ->request('GET', $request->getPath());

            if ($response->getStatusCode() >= 400) {
                throw new BadResponseException(
                    $response->getStatusCode().' on '.$response->getInfo('url'),
                    $response->getStatusCode()
                );
            }

            $list = \json_decode($response->getContent());

            $model = [];
            foreach ($list as $item) {
                $model[] = Model\User\AnimeListItem::factory($item);
            }
            return $model;
        } catch (\Exception $e) {
            throw new BadResponseException(
                $e->getCode().' on '.$request->getPath(),
                $e->getCode()
            );
        }
    }


    /**
     * @param  Request\User\UserMangaListRequest $request
     * @return array
     * @throws BadResponseException
     */
    public function getUserMangaList(Request\User\UserMangaListRequest $request): array
    {
        try {
            $response = $this->httpClient
                ->request('GET', $request->getPath());

            if ($response->getStatusCode() >= 400) {
                throw new BadResponseException(
                    $response->getStatusCode().' on '.$response->getInfo('url'),
                    $response->getStatusCode()
                );
            }

            $list = \json_decode($response->getContent());

            $model = [];
            foreach ($list as $item) {
                $model[] = Model\User\MangaListItem::factory($item);
            }
            return $model;
        } catch (\Exception $e) {
            throw new BadResponseException(
                $e->getCode().' on '.$request->getPath(),
                $e->getCode()
            );
        }
    }


    /**
     * @param  Request\Anime\AnimeRecentlyUpdatedByUsersRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeRecentlyUpdatedByUsers(Request\Anime\AnimeRecentlyUpdatedByUsersRequest $request): Model\Anime\AnimeUserUpdates
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
     * @param  Request\Manga\MangaRecentlyUpdatedByUsersRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaRecentlyUpdatedByUsers(Request\Manga\MangaRecentlyUpdatedByUsersRequest $request): Model\Manga\MangaUserUpdates
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
     * @param  Request\Anime\AnimeRecommendationsRequest $request
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
     * @param  Request\Manga\MangaRecommendationsRequest $request
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
     * @return Model\Club\UserList
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getClubUsers(Request\Club\UserListRequest $request): Model\Club\UserList
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());

        try {
            $parser = new Parser\Club\UserListParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }


    /**
     * @param Request\Anime\AnimeReviewsRequest $request
     * @return Model\Anime\AnimeReviews
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeReviews(Request\Anime\AnimeReviewsRequest $request): Model\Anime\AnimeReviews
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
     * @return Model\Manga\MangaReviews
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaReviews(Request\Manga\MangaReviewsRequest $request): Model\Manga\MangaReviews
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
     * @param  Request\Club\ClubRequest $request
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

    /**
     * @param  Request\Anime\AnimeEpisodeRequest $request
     * @return Model\Anime\AnimeEpisode
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeEpisode(Request\Anime\AnimeEpisodeRequest $request): Model\Anime\AnimeEpisode
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());

        try {
            $parser = new Parser\Anime\AnimeEpisodeParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param  Request\Producer\ProducersRequest $request
     * @return Model\Producer\ProducerList
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getProducers(Request\Producer\ProducersRequest $request): Model\Producer\ProducerList
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());

        try {
            $parser = new Parser\Producer\ProducerListParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param  Request\Magazine\MagazinesRequest  $request
     * @return Model\Magazine\MagazineList
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMagazines(Request\Magazine\MagazinesRequest $request): Model\Magazine\MagazineList
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());

        try {
            $parser = new Parser\Magazine\MagazineListParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param  Request\Genre\AnimeGenresRequest  $request
     * @return Model\Genre\AnimeGenreList
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeGenres(Request\Genre\AnimeGenresRequest $request): Model\Genre\AnimeGenreList
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());

        try {
            $parser = new Parser\Genre\AnimeGenreListParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param  Request\Genre\MangaGenresRequest  $request
     * @return Model\Genre\MangaGenreList
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaGenres(Request\Genre\MangaGenresRequest $request): Model\Genre\MangaGenreList
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());

        try {
            $parser = new Parser\Genre\MangaGenreListParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Reviews\ReviewsRequest $request
     *
     * @return
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getReviews(Request\Reviews\ReviewsRequest $request): Model\Reviews\Reviews
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Reviews\ReviewsParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Recommendations\RecentRecommendationsRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getRecentRecommendations(Request\Recommendations\RecentRecommendationsRequest $request): Model\Recommendations\RecentRecommendations
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Recommendations\RecentRecommendationsParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Search\UserSearchRequest $request
     *
     * @return Model\Search\UserSearch
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getUserSearch(Request\Search\UserSearchRequest $request): Model\Search\UserSearch
    {
        // Returns 404 when there are no results
        try {
            $crawler = $this->ghoutte->request('GET', $request->getPath());
        } catch (\Exception $e) {
            if ($e->getCode() === 404) {
                return new Model\Search\UserSearch();
            }
            throw $e;
        }

        try {
            $parser = new Parser\Search\UserSearchParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\User\RecentlyOnlineUsersRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getRecentOnlineUsers(Request\User\RecentlyOnlineUsersRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Search\UserSearchParser($crawler);

            return $parser->getResults();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\User\UsernameByIdRequest $request
     * @return Model\Common\UserMetaBasic
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getUsernameById(Request\User\UsernameByIdRequest $request) : Model\Common\UserMetaBasic
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\User\UsernameByIdParser($crawler);

            return $parser->getUser();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\User\UserReviewsRequest $request
     * @return Model\User\Reviews\UserReviews
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getUserReviews(Request\User\UserReviewsRequest $request) : Model\User\Reviews\UserReviews
    {
        try {
            $crawler = $this->ghoutte->request('GET', $request->getPath());
        } catch (\Exception $e) {
            if ($e->getCode() === 404) {
                return Model\User\Reviews\UserReviews::mock();
            }
            throw $e;
        }
        try {
            $parser = new Parser\User\Reviews\UserReviewsParser($crawler);
            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Watch\RecentEpisodesRequest $request
     * @return Model\Watch\Episodes
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getRecentEpisodes(Request\Watch\RecentEpisodesRequest $request) : Model\Watch\Episodes
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Watch\WatchEpisodesParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Watch\PopularEpisodesRequest $request
     * @return Model\Watch\Episodes
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getPopularEpisodes(Request\Watch\PopularEpisodesRequest $request) : Model\Watch\Episodes
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Watch\WatchEpisodesParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Watch\RecentPromotionalVideosRequest $request
     * @return Model\Watch\PromotionalVideos
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getRecentPromotionalVideos(
        Request\Watch\RecentPromotionalVideosRequest $request
    ) : Model\Watch\PromotionalVideos {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Watch\WatchPromotionalVideosParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\Watch\PopularPromotionalVideosRequest $request
     * @return Model\Watch\PromotionalVideos
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getPopularPromotionalVideos(
        Request\Watch\PopularPromotionalVideosRequest $request
    ) : Model\Watch\PromotionalVideos {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Watch\WatchPromotionalVideosParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }


    /**
     * @param Request\User\UserRecommendationsRequest $request
     * @return Model\Recommendations\UserRecommendations
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getUserRecommendations(Request\User\UserRecommendationsRequest $request): Model\Recommendations\UserRecommendations
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        try {
            $parser = new Parser\Recommendations\UserRecommendationsParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }

    /**
     * @param Request\User\UserClubsRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getUserClubs(Request\User\UserClubsRequest $request) : array
    {
        // user clubs page returns 404 when there are none added
        try {
            $crawler = $this->ghoutte->request('GET', $request->getPath());
        } catch (\Exception $e) {
            if ($e->getCode() === 404) {
                return [];
            }

            throw $e;
        }
        try {
            $parser = new Parser\User\ClubParser($crawler);

            return $parser->getClubs();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }
}
