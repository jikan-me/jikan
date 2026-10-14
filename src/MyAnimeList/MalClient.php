<?php

/**
 *    Jikan - MyAnimeList.net Unofficial API
 *
 *    Jikan is an unofficial MAL API which scrapes data requested from MAL.
 *    No authentication is needed for utilizing this library and neither are any authentication based features supported.
 *
 *    Jikan is NOT affiliated with MyAnimeList.net
 *    This library does not perform any rate limitations or caching, so use it responsibly.
 */

namespace Jikan\MyAnimeList;

use Jikan\Exception\BadResponseException;
use Jikan\Exception\ParserException;
use Jikan\Http\HttpClientWrapper;
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
     * @var HttpClientWrapper
     */
    protected HttpClientWrapper $httpClientWrapper;

    /**
     * @var HttpClientInterface
     */
    protected HttpClientInterface $httpClient;

    /**
     * MalClient constructor.
     *
     * @param HttpClientInterface|null $httpClient
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(?HttpClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?? HttpClient::create();
        $this->httpClientWrapper = new HttpClientWrapper($this->httpClient);
    }

    /**
     * @param  Request\Anime\AnimeRequest $request
     * @return Model\Anime\Anime
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnime(Request\Anime\AnimeRequest $request): Model\Anime\Anime
    {
        $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
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
            $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
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
        return $this->parse($request, Parser\Anime\VideosParser::class);
    }

    /**
     * @param Request\Anime\AnimeVideosEpisodesRequest $request
     * @return Model\Anime\AnimeVideosEpisodes
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeVideosEpisodes(Request\Anime\AnimeVideosEpisodesRequest $request): Model\Anime\AnimeVideosEpisodes
    {
        $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
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
        return $this->parse($request, Parser\Manga\MangaParser::class);
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

        $crawler = $this->httpClientWrapper->request('GET', $request->getPath());

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

        $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
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
        return $this->parse($request, Parser\User\Profile\UserProfileParser::class);
    }

    /**
     * @param Request\User\UserFriendsRequest $request
     * @return Model\User\Friends
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getUserFriends(Request\User\UserFriendsRequest $request): Model\User\Friends
    {
        return $this->parse($request, Parser\User\Friends\FriendsParser::class);
    }

    /**
     * @param  Request\Seasonal\SeasonalRequest $request
     * @return Model\Seasonal\Seasonal
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getSeasonal(Request\Seasonal\SeasonalRequest $request): Model\Seasonal\Seasonal
    {
        return $this->parse($request, Parser\Seasonal\SeasonalParser::class);
    }

    /**
     * @param  Request\Producer\ProducerRequest $request
     * @return Model\Producer\Producer
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getProducer(Request\Producer\ProducerRequest $request): Model\Producer\Producer
    {
        return $this->parse($request, Parser\Producer\ProducerParser::class);
    }

    /**
     * @param  Request\Magazine\MagazineRequest $request
     * @return Model\Magazine\Magazine
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMagazine(Request\Magazine\MagazineRequest $request): Model\Magazine\Magazine
    {
        return $this->parse($request, Parser\Magazine\MagazineParser::class);
    }

    /**
     * @param  Request\Genre\AnimeGenreRequest $request
     * @return Model\Genre\AnimeGenre
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeGenre(Request\Genre\AnimeGenreRequest $request): Model\Genre\AnimeGenre
    {
        return $this->parse($request, Parser\Genre\AnimeGenreParser::class);
    }

    /**
     * @param  Request\Genre\MangaGenreRequest $request
     * @return Model\Genre\MangaGenre
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaGenre(Request\Genre\MangaGenreRequest $request): Model\Genre\MangaGenre
    {
        return $this->parse($request, Parser\Genre\MangaGenreParser::class);
    }

    /**
     * @param  Request\Schedule\ScheduleRequest $request
     * @return Model\Schedule\Schedule
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getSchedule(Request\Schedule\ScheduleRequest $request): Model\Schedule\Schedule
    {
        return $this->parse($request, Parser\Schedule\ScheduleParser::class);
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
        return $this->parse($request, Parser\Anime\CharactersAndStaffParser::class);
    }

    /**
     * @param  Request\Anime\AnimePicturesRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimePictures(Request\Anime\AnimePicturesRequest $request): array
    {
        return $this->parse($request, Parser\Common\PicturesPageParser::class);
    }

    /**
     * @param  Request\Manga\MangaPicturesRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaPictures(Request\Manga\MangaPicturesRequest $request): array
    {
        return $this->parse($request, Parser\Common\PicturesPageParser::class);
    }

    /**
     * @param  Request\Character\CharacterPicturesRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getCharacterPictures(Request\Character\CharacterPicturesRequest $request): array
    {
        return $this->parse($request, Parser\Common\DefaultPicturesPageParser::class);
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
        return $this->parse($request, Parser\Common\DefaultPicturesPageParser::class);
    }

    /**
     * @param Request\RequestInterface $request
     *
     * @return Model\News\ResourceNewsList
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getNewsList(Request\RequestInterface $request): Model\News\ResourceNewsList
    {
        return $this->parse($request, Parser\News\ResourceNewsListParser::class);
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
        // MAL answers a search with no matches with a 404 page
        try {
            $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
        } catch (BadResponseException $e) {
            if ($e->getCode() === 404) {
                return Model\Search\AnimeSearch::mock();
            }
            throw $e;
        }

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
    public function getAnimeSearchAlt(Request\Search\AnimeSearchRequest $request): Model\Search\AnimeSearch
    {
        return $this->parse($request, Parser\Search\AnimeSearchParser::class);
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
        // MAL answers a search with no matches with a 404 page
        try {
            return $this->parse($request, Parser\Search\MangaSearchParser::class);
        } catch (BadResponseException $e) {
            if ($e->getCode() === 404) {
                return Model\Search\MangaSearch::mock();
            }
            throw $e;
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
            $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
        } catch (BadResponseException $e) {
            if ($e->getCode() === 404) {
                return Model\Search\CharacterSearch::mock();
            }
            throw $e;
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
            $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
        } catch (BadResponseException $e) {
            if ($e->getCode() === 404) {
                return Model\Search\PersonSearch::mock();
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
        $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
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
        return $this->parse($request, Parser\Top\TopAnimeParser::class);
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
        return $this->parse($request, Parser\Top\TopMangaParser::class);
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
        return $this->parse($request, Parser\Top\TopCharactersParser::class);
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
        return $this->parse($request, Parser\Top\TopPeopleParser::class);
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
        return $this->parse($request, Parser\Anime\AnimeStatsParser::class);
    }

    /**
     * @param  Request\Manga\MangaStatsRequest $request
     * @return Model\Manga\MangaStats
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaStats(Request\Manga\MangaStatsRequest $request): Model\Manga\MangaStats
    {
        return $this->parse($request, Parser\Manga\MangaStatsParser::class);
    }

    /**
     * @param  Request\Anime\AnimeForumRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeForum(Request\Anime\AnimeForumRequest $request): array
    {
        $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
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
        $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
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
        $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
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
        $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
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
        return $this->parse($request, Parser\SeasonList\SeasonListParser::class);
    }

    /**
     * @param  Request\User\UserHistoryRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getUserHistory(Request\User\UserHistoryRequest $request): array
    {
        return $this->parse($request, Parser\User\History\HistoryParser::class);
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
                    $response->getStatusCode() . ' on ' . $response->getInfo('url'),
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
                $e->getCode() . ' on ' . $request->getPath(),
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
                    $response->getStatusCode() . ' on ' . $response->getInfo('url'),
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
                $e->getCode() . ' on ' . $request->getPath(),
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
        return $this->parse($request, Parser\Anime\AnimeRecentlyUpdatedByUsersParser::class);
    }

    /**
     * @param  Request\Manga\MangaRecentlyUpdatedByUsersRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaRecentlyUpdatedByUsers(Request\Manga\MangaRecentlyUpdatedByUsersRequest $request): Model\Manga\MangaUserUpdates
    {
        return $this->parse($request, Parser\Manga\MangaRecentlyUpdatedByUsersParser::class);
    }

    /**
     * @param  Request\Anime\AnimeRecommendationsRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeRecommendations(Request\Anime\AnimeRecommendationsRequest $request): array
    {
        return $this->parse($request, Parser\Common\Recommendations::class);
    }

    /**
     * @param  Request\Manga\MangaRecommendationsRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaRecommendations(Request\Manga\MangaRecommendationsRequest $request): array
    {
        return $this->parse($request, Parser\Common\Recommendations::class);
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
        return $this->parse($request, Parser\Club\UserListParser::class);
    }


    /**
     * @param Request\Anime\AnimeReviewsRequest $request
     * @return Model\Anime\AnimeReviews
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeReviews(Request\Anime\AnimeReviewsRequest $request): Model\Anime\AnimeReviews
    {
        return $this->parse($request, Parser\Anime\AnimeReviewsParser::class);
    }


    /**
     * @param Request\Manga\MangaReviewsRequest $request
     * @return Model\Manga\MangaReviews
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaReviews(Request\Manga\MangaReviewsRequest $request): Model\Manga\MangaReviews
    {
        return $this->parse($request, Parser\Manga\MangaReviewsParser::class);
    }

    /**
     * @param  Request\Club\ClubRequest $request
     * @return Model\Club\Club
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getClub(Request\Club\ClubRequest $request): Model\Club\Club
    {
        return $this->parse($request, Parser\Club\ClubParser::class);
    }

    /**
     * @param  Request\Anime\AnimeEpisodeRequest $request
     * @return Model\Anime\AnimeEpisode
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeEpisode(Request\Anime\AnimeEpisodeRequest $request): Model\Anime\AnimeEpisode
    {
        $crawler = $this->httpClientWrapper->request('GET', $request->getPath());

        try {
            $parser = new Parser\Anime\AnimeEpisodeParser($crawler);

            return $parser->getModel();
        } catch (\Exception $e) {

            // MAL returns HTTP 200 for a page that doesn't exist,
            // so we check for a forced 404 exception thrown by the parser
            // in order to throw a proper 404 exception
            if ($e->getCode() === 404) {
                throw new BadResponseException('404 on ' . $request->getPath(), 404);
            }

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
        return $this->parse($request, Parser\Producer\ProducerListParser::class);
    }

    /**
     * @param  Request\Magazine\MagazinesRequest  $request
     * @return Model\Magazine\MagazineList
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMagazines(Request\Magazine\MagazinesRequest $request): Model\Magazine\MagazineList
    {
        return $this->parse($request, Parser\Magazine\MagazineListParser::class);
    }

    /**
     * @param  Request\Genre\AnimeGenresRequest  $request
     * @return Model\Genre\AnimeGenreList
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getAnimeGenres(Request\Genre\AnimeGenresRequest $request): Model\Genre\AnimeGenreList
    {
        return $this->parse($request, Parser\Genre\AnimeGenreListParser::class);
    }

    /**
     * @param  Request\Genre\MangaGenresRequest  $request
     * @return Model\Genre\MangaGenreList
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getMangaGenres(Request\Genre\MangaGenresRequest $request): Model\Genre\MangaGenreList
    {
        return $this->parse($request, Parser\Genre\MangaGenreListParser::class);
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
        return $this->parse($request, Parser\Reviews\ReviewsParser::class);
    }

    /**
     * @param Request\Recommendations\RecentRecommendationsRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getRecentRecommendations(Request\Recommendations\RecentRecommendationsRequest $request): Model\Recommendations\RecentRecommendations
    {
        return $this->parse($request, Parser\Recommendations\RecentRecommendationsParser::class);
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
            $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
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
        $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
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
    public function getUsernameById(Request\User\UsernameByIdRequest $request): Model\Common\UserMetaBasic
    {
        $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
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
    public function getUserReviews(Request\User\UserReviewsRequest $request): Model\User\Reviews\UserReviews
    {
        try {
            $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
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
    public function getRecentEpisodes(Request\Watch\RecentEpisodesRequest $request): Model\Watch\Episodes
    {
        return $this->parse($request, Parser\Watch\WatchEpisodesParser::class);
    }

    /**
     * @param Request\Watch\PopularEpisodesRequest $request
     * @return Model\Watch\Episodes
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getPopularEpisodes(Request\Watch\PopularEpisodesRequest $request): Model\Watch\Episodes
    {
        return $this->parse($request, Parser\Watch\WatchEpisodesParser::class);
    }

    /**
     * @param Request\Watch\RecentPromotionalVideosRequest $request
     * @return Model\Watch\PromotionalVideos
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getRecentPromotionalVideos(
        Request\Watch\RecentPromotionalVideosRequest $request
    ): Model\Watch\PromotionalVideos {
        return $this->parse($request, Parser\Watch\WatchPromotionalVideosParser::class);
    }

    /**
     * @param Request\Watch\PopularPromotionalVideosRequest $request
     * @return Model\Watch\PromotionalVideos
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getPopularPromotionalVideos(
        Request\Watch\PopularPromotionalVideosRequest $request
    ): Model\Watch\PromotionalVideos {
        return $this->parse($request, Parser\Watch\WatchPromotionalVideosParser::class);
    }


    /**
     * @param Request\User\UserRecommendationsRequest $request
     * @return Model\Recommendations\UserRecommendations
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getUserRecommendations(Request\User\UserRecommendationsRequest $request): Model\Recommendations\UserRecommendations
    {
        return $this->parse($request, Parser\Recommendations\UserRecommendationsParser::class);
    }

    /**
     * @param Request\User\UserClubsRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getUserClubs(Request\User\UserClubsRequest $request): array
    {
        // user clubs page returns 404 when there are none added
        try {
            $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
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

    /**
     * @param Request\News\RecentNewsRequest $request
     * @return Model\News\NewsList
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getRecentNews(Request\News\RecentNewsRequest $request): Model\News\NewsList
    {
        return $this->parse($request, Parser\News\NewsListParser::class);
    }

    /**
     * @param Request\News\NewsByTagRequest $request
     * @return Model\News\NewsList
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getNewsByTag(Request\News\NewsByTagRequest $request): Model\News\NewsList
    {
        return $this->parse($request, Parser\News\NewsListParser::class);
    }

    /**
     * @param Request\Search\NewsByTagRequest $request
     * @return Model\News\NewsList
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getNewsSearch(Request\Search\NewsSearchRequest $request): Model\News\NewsList
    {
        return $this->parse($request, Parser\News\NewsListParser::class);
    }

    /**
     * @param Request\News\NewsTagsRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getNewsTags(Request\News\NewsTagsRequest $request): array
    {
        return $this->parse($request, Parser\News\NewsTagsParser::class);
    }


    /**
     * @param Request\News\NewsRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getNews(Request\News\NewsRequest $request): Model\News\ResourceNews
    {
        return $this->parse($request, Parser\News\NewsParser::class);
    }

    /**
     * @param Request\Article\PinnedArticlesRequest $request
     * @return Model\Article\PinnedArticleList
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getPinnedArticles(Request\Article\PinnedArticlesRequest $request): Model\Article\PinnedArticleList
    {
        return $this->parse($request, Parser\Article\PinnedArticleListParser::class);
    }

    /**
     * @param Request\Article\RecentArticleRequest $request
     * @return Model\Article\ArticleList
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getRecentArticles(Request\Article\RecentArticleRequest $request): Model\Article\ArticleList
    {
        return $this->parse($request, Parser\Article\ArticleListParser::class);
    }


    /**
     * @param Request\Article\ArticlesByTagRequest $request
     * @return Model\Article\ArticleList
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getArticlesByTag(Request\Article\ArticlesByTagRequest $request): Model\Article\ArticleList
    {
        return $this->parse($request, Parser\Article\ArticleListParser::class);
    }

    /**
     * @param Request\Search\ArticleSearchRequest $request
     * @return Model\Article\ArticleList
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getArticleSearch(Request\Search\ArticleSearchRequest $request): Model\Article\ArticleList
    {
        return $this->parse($request, Parser\Article\ArticleListParser::class);
    }


    /**
     * @param Request\Article\ArticleTagsRequest $request
     * @return array
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getArticleTags(Request\Article\ArticleTagsRequest $request): array
    {
        return $this->parse($request, Parser\Article\ArticleTagsParser::class);
    }

    /**
     * @param Request\Article\ArticleRequest $request
     * @return Model\Article\ResourceArticle
     * @throws BadResponseException
     * @throws ParserException
     */
    public function getArticle(Request\Article\ArticleRequest $request): Model\Article\ResourceArticle
    {
        return $this->parse($request, Parser\Article\ArticleParser::class);
    }
    /**
     * Fetches the page for a request, parses it and returns the parser's model.
     *
     * @param  Request\RequestInterface $request
     * @param  class-string             $parserClass a parser whose constructor takes the page crawler and which has getModel()
     * @return mixed
     * @throws BadResponseException
     * @throws ParserException
     */
    private function parse(Request\RequestInterface $request, string $parserClass): mixed
    {
        $crawler = $this->httpClientWrapper->request('GET', $request->getPath());
        try {
            return new $parserClass($crawler)->getModel();
        } catch (\Exception $e) {
            throw ParserException::fromRequest($request, $e);
        }
    }
}
