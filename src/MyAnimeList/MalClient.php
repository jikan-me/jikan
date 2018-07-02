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
        $parser = new Parser\UserProfile\UserProfileParser($crawler);

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
}
