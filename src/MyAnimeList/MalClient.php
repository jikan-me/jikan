<?php

namespace Jikan\MyAnimeList;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Jikan\Model;
use Jikan\Parser;
use Jikan\Request;

/**
 * Class MalClient
 */
class MalClient
{
    /**
     * @var Client
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
        $this->ghoutte = new Client();
        if ($guzzle !== null) {
            $this->ghoutte->setClient($guzzle);
        }
    }

    /**
     * @param Request\Anime\AnimeRequest $request
     *
     * @return \Jikan\Model\Anime\Anime
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getAnime(Request\Anime\AnimeRequest $request): Model\Anime\Anime
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Anime\AnimeParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Anime\AnimeEpisodesRequest $request
     *
     * @return \Jikan\Model\Anime\Episodes
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getAnimeEpisodes(Request\Anime\AnimeEpisodesRequest $request): Model\Anime\Episodes
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Anime\EpisodesParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Anime\AnimeVideosRequest $request
     *
     * @return \Jikan\Model\Anime\AnimeVideos
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getAnimeVideos(Request\Anime\AnimeVideosRequest $request): Model\Anime\AnimeVideos
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Anime\VideosParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Manga\MangaRequest $request
     *
     * @return \Jikan\Model\Manga\Manga
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getManga(Request\Manga\MangaRequest $request): Model\Manga\Manga
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Manga\MangaParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Character\CharacterRequest $request
     *
     * @return \Jikan\Model\Character\Character
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * CharacterPictures
     */
    public function getCharacter(Request\Character\CharacterRequest $request): Model\Character\Character
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Character\CharacterParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Person\PersonRequest $request
     *
     * @return \Jikan\Model\Person\Person
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getPerson(Request\Person\PersonRequest $request): Model\Person\Person
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Person\PersonParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\User\UserProfileRequest $request
     *
     * @return \Jikan\Model\User\UserProfile
     * @throws \InvalidArgumentException
     */
    public function getUserProfile(Request\User\UserProfileRequest $request): Model\User\UserProfile
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\User\Profile\UserProfileParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\User\UserFriendsRequest $request
     *
     * @return \Jikan\Model\User\Friend[]
     * @throws \InvalidArgumentException
     */
    public function getUserFriends(Request\User\UserFriendsRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\User\Friends\FriendsParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Seasonal\SeasonalRequest $request
     *
     * @return \Jikan\Model\Seasonal\Seasonal
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getSeasonal(Request\Seasonal\SeasonalRequest $request): Model\Seasonal\Seasonal
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Seasonal\SeasonalParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Producer\ProducerRequest $request
     *
     * @return \Jikan\Model\Producer\Producer
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getProducer(Request\Producer\ProducerRequest $request): Model\Producer\Producer
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Producer\ProducerParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Magazine\MagazineRequest $request
     *
     * @return \Jikan\Model\Magazine\Magazine
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMagazine(Request\Magazine\MagazineRequest $request): Model\Magazine\Magazine
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Magazine\MagazineParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Genre\AnimeGenreRequest $request
     *
     * @return \Jikan\Model\Anime\AnimeGenre
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getAnimeGenre(Request\Genre\AnimeGenreRequest $request): Model\Anime\AnimeGenre
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());

        $parser = new Parser\Genre\AnimeGenreParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Genre\MangaGenreRequest $request
     *
     * @return \Jikan\Model\Genre\MangaGenre
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMangaGenre(Request\Genre\MangaGenreRequest $request): Model\Genre\MangaGenre
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());

        $parser = new Parser\Genre\MangaGenreParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Schedule\ScheduleRequest $request
     *
     * @return \Jikan\Model\Shedule\Schedule
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getSchedule(Request\Schedule\ScheduleRequest $request): Model\Shedule\Schedule
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Schedule\ScheduleParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Anime\AnimeCharactersAndStaffRequest $request
     *
     * @return \Jikan\Model\Anime\AnimeCharactersAndStaff
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getCharactersAndStaff(
        Request\Anime\AnimeCharactersAndStaffRequest $request
    ): Model\Anime\AnimeCharactersAndStaff {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Anime\CharactersAndStaffParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Anime\AnimePicturesRequest $request
     *
     * @return \Jikan\Model\Common\Picture[]
     * @throws \InvalidArgumentException
     */
    public function getAnimePictures(Request\Anime\AnimePicturesRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Common\PicturesPageParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Manga\MangaPicturesRequest $request
     *
     * @return \Jikan\Model\Common\Picture[]
     * @throws \InvalidArgumentException
     */
    public function getMangaPictures(Request\Manga\MangaPicturesRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Common\PicturesPageParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Character\CharacterPicturesRequest $request
     *
     * @return \Jikan\Model\Common\Picture[]
     * @throws \InvalidArgumentException
     */
    public function getCharacterPictures(Request\Character\CharacterPicturesRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Common\PicturesPageParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Person\PersonPicturesRequest $request
     *
     * @return \Jikan\Model\Common\Picture[]
     * @throws \InvalidArgumentException
     */
    public function getPersonPictures(Request\Person\PersonPicturesRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Common\PicturesPageParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\RequestInterface $request
     *
     * @return Model\News\NewsListItem[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getNewsList(Request\RequestInterface $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\News\NewsListParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Search\AnimeSearchRequest $request
     *
     * @return Model\Search\AnimeSearch
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getAnimeSearch(Request\Search\AnimeSearchRequest $request): Model\Search\AnimeSearch
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Search\AnimeSearchParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Search\MangaSearchRequest $request
     *
     * @return Model\Search\MangaSearch
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMangaSearch(Request\Search\MangaSearchRequest $request): Model\Search\MangaSearch
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Search\MangaSearchParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Search\CharacterSearchRequest $request
     *
     * @return Model\Search\CharacterSearch
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getCharacterSearch(Request\Search\CharacterSearchRequest $request): Model\Search\CharacterSearch
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Search\CharacterSearchParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Search\PersonSearchRequest $request
     *
     * @return Model\Search\PersonSearch
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getPersonSearch(Request\Search\PersonSearchRequest $request): Model\Search\PersonSearch
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Search\PersonSearchParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param $request
     *
     * @return Model\Manga\CharacterListItem[]
     * @throws \InvalidArgumentException
     */
    public function getMangaCharacters(Request\Manga\MangaCharactersRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Manga\CharactersParser($crawler);

        return $parser->getCharacters();
    }

    /**
     * @param Request\Top\TopAnimeRequest $request
     *
     * @return Model\Top\TopAnime[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getTopAnime(Request\Top\TopAnimeRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Top\TopAnimeParser($crawler);

        return $parser->getTopAnime();
    }

    /**
     * @param Request\Top\TopMangaRequest $request
     *
     * @return Model\Top\TopManga[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getTopManga(Request\Top\TopMangaRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Top\TopMangaParser($crawler);

        return $parser->getTopManga();
    }

    /**
     * @param Request\Top\TopCharactersRequest $request
     *
     * @return Model\Top\TopCharacter[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getTopCharacters(Request\Top\TopCharactersRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Top\TopCharactersParser($crawler);

        return $parser->getTopCharacters();
    }

    /**
     * @param Request\Top\TopPeopleRequest $request
     *
     * @return Model\Top\TopPeople[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getTopPeople(Request\Top\TopPeopleRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Top\TopPeopleParser($crawler);

        return $parser->getTopPeople();
    }

    /**
     * @param Request\Anime\AnimeStatsRequest $request
     *
     * @return Model\Anime\AnimeStats
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getAnimeStats(Request\Anime\AnimeStatsRequest $request): Model\Anime\AnimeStats
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Anime\AnimeStatsParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Manga\MangaStatsRequest $request
     *
     * @return Model\Manga\MangaStats
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMangaStats(Request\Manga\MangaStatsRequest $request): Model\Manga\MangaStats
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Manga\MangaStatsParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Anime\AnimeForumRequest $request
     *
     * @return Model\Forum\ForumTopic[]
     * @throws \InvalidArgumentException
     */
    public function getAnimeForumTopics(Request\Anime\AnimeForumRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Forum\ForumPageParser($crawler);

        return $parser->getTopics();
    }

    /**
     * @param Request\Manga\MangaForumRequest $request
     *
     * @return Model\Forum\ForumTopic[]
     * @throws \InvalidArgumentException
     */
    public function getMangaForumTopics(Request\Manga\MangaForumRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Forum\ForumPageParser($crawler);

        return $parser->getTopics();
    }

    /**
     * @param Request\Anime\AnimeMoreInfoRequest $request
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAnimeMoreInfo(Request\Anime\AnimeMoreInfoRequest $request): string
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Anime\MoreInfoParser($crawler);

        return $parser->getModel()->getMoreInfo();
    }

    /**
     * @param Request\SeasonList\SeasonListRequest $request
     *
     * @return Model\SeasonList\SeasonListItem[] An array of SeasonListItem instances
     * @throws \InvalidArgumentException
     */
    public function getSeasonList(Request\SeasonList\SeasonListRequest $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\SeasonList\SeasonListParser($crawler);

        return $parser->getModel();
    }
}
