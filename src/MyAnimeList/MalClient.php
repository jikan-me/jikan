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
     */
    public function __construct(GuzzleClient $guzzle = null)
    {
        $this->ghoutte = new Client();
        if ($guzzle !== null) {
            $this->ghoutte->setClient($guzzle);
        }
    }

    /**
     * @param Request\Anime $request
     *
     * @return Model\Anime
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getAnime(Request\Anime $request): Model\Anime
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Anime\AnimeParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\AnimeEpisodes $request
     *
     * @return Model\Episodes
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getAnimeEpisodes(Request\AnimeEpisodes $request): Model\Episodes
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Anime\EpisodesParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\AnimeVideos $request
     *
     * @return Model\AnimeVideos
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getAnimeVideos(Request\AnimeVideos $request): Model\AnimeVideos
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Anime\VideosParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Manga $request
     *
     * @return Model\Manga
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getManga(Request\Manga $request): Model\Manga
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Manga\MangaParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Character $request
     *
     * @return Model\Character
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getCharacter(Request\Character $request): Model\Character
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Character\CharacterParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Person $request
     *
     * @return Model\Person
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getPerson(Request\Person $request): Model\Person
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Person\PersonParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\UserProfile $request
     *
     * @return Model\UserProfile
     * @throws \InvalidArgumentException
     */
    public function getUserProfile(Request\UserProfile $request): Model\UserProfile
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\User\Profile\UserProfileParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Seasonal $request
     *
     * @return Model\Seasonal
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getSeasonal(Request\Seasonal $request): Model\Seasonal
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Seasonal\SeasonalParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Producer $request
     *
     * @return Model\Producer
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getProducer(Request\Producer $request): Model\Producer
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Producer\ProducerParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Magazine $request
     *
     * @return Model\Magazine
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMagazine(Request\Magazine $request): Model\Magazine
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Magazine\MagazineParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\AnimeGenre $request
     *
     * @return Model\AnimeGenre
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getAnimeGenre(Request\AnimeGenre $request): Model\AnimeGenre
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());

        $parser = new Parser\Genre\AnimeGenreParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\MangaGenre $request
     *
     * @return Model\MangaGenre
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMangaGenre(Request\MangaGenre $request): Model\MangaGenre
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());

        $parser = new Parser\Genre\MangaGenreParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Schedule $request
     *
     * @return Model\Schedule
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getSchedule(Request\Schedule $request): Model\Schedule
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Schedule\ScheduleParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\UserFriends $request
     *
     * @return Model\Friend[]
     * @throws \InvalidArgumentException
     */
    public function getUserFriends(Request\UserFriends $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\User\Friends\FriendsParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\CharactersAndStaff $request
     *
     * @return Model\CharactersAndStaff
     */
    public function getCharactersAndStaff(Request\CharactersAndStaff $request): Model\CharactersAndStaff
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Anime\CharactersAndStaffParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\AnimePictures $request
     *
     * @return Model\Picture[]
     */
    public function getAnimePictures(Request\AnimePictures $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Common\PicturesPageParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\MangaPictures $request
     *
     * @return Model\Picture[]
     */
    public function getMangaPictures(Request\MangaPictures $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Common\PicturesPageParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\CharacterPictures $request
     *
     * @return Model\Picture[]
     */
    public function getCharacterPictures(Request\CharacterPictures $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Common\PicturesPageParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\PersonPictures $request
     *
     * @return Model\Picture[]
     */
    public function getPersonPictures(Request\PersonPictures $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Common\PicturesPageParser($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\RequestInterface $request
     *
     * @return Model\News\NewsListItem[]
     */
    public function getNewsList(Request\RequestInterface $request): array
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\News\NewsListParser($crawler);

        return $parser->getModel();
    }
}
