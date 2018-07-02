<?php
/**
 *    Jikan - MyAnimeList Unofficial API v2
 *    Developed by Irfan | @irfanDahir
 *
 *    This is an unofficial MAL API that provides the features that the official one lacks.
 *    Jikan scraps web pages through a modular method, parses the data you require from MAL and returns it back as a
 * PHP/JSON array/object. Jikan parses the data MAL web pages and returns it as a PHP Array No authentication is needed
 * for utilizing this library.
 *
 *    Jikan is NOT affiliated with MyAnimeList.
 *   This library does not perform any rate limitations, so use it responsibly.
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
     * @param Request\Anime $request
     *
     * @return Model\Anime
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Anime(Request\Anime $request): Model\Anime
    {
        return $this->myanimelist->getAnime($request);
    }

    /**
     * @param Request\Manga $request
     *
     * @return Model\Manga
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Manga(Request\Manga $request): Model\Manga
    {
        return $this->myanimelist->getManga($request);
    }

    /**
     * @param Request\Character $request
     *
     * @return Model\Character
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Character(Request\Character $request): Model\Character
    {
        return $this->myanimelist->getCharacter($request);
    }

    /**
     * @param Request\Person $request
     *
     * @return Model\Person
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Person(Request\Person $request): Model\Person
    {
        return $this->myanimelist->getPerson($request);
    }

    /**
     * @param Request\UserProfile $request
     *
     * @return Model\UserProfile
     * @throws \InvalidArgumentException
     */
    public function UserProfile(Request\UserProfile $request): Model\UserProfile
    {
        return $this->myanimelist->getUserProfile($request);
    }

    /**
     * @param Request\Seasonal $request
     *
     * @return Model\Seasonal
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Seasonal(Request\Seasonal $request): Model\Seasonal
    {
        return $this->myanimelist->getSeasonal($request);
    }

    /**
     * @param Request\Producer $request
     *
     * @return Model\Producer
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Producer(Request\Producer $request): Model\Producer
    {
        return $this->myanimelist->getProducer($request);
    }


    /**
     * @param Request\Genre $request
     *
     * @return Model\Genre
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Genre(Request\Genre $request): Model\Genre
    {
        return $this->myanimelist->getGenre($request);
    }

    /**
     * @param Request\Schedule $request
     *
     * @return Model\Schedule
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function Schedule(Request\Schedule $request): Model\Schedule
    {
        return $this->myanimelist->getSchedule($request);
    }
}
