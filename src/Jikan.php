<?php
/**
 *   Jikan - MyAnimeList Unofficial API
 *   Developed by Irfan | irfan.dahir.co
 *
 *   This is an unofficial MAL API that provides the features that the official one lacks.
 *   Jikan scraps web pages through a modular method, parses the data you require from MAL and returns it back as a
 * PHP/JSON array/object. Jikan parses the data MAL web pages and returns it as a PHP Array No authentication is needed
 * for utilizing this library.
 *
 *   Jikan is NOT affiliated with MyAnimeList.
 *   This library does not perform any rate limitations, so use it responsibly.
 */

namespace Jikan;

require __DIR__.'/config.php';

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Jikan\Helper\SearchConfig;

/**
 * Class Jikan
 *
 * @package Jikan
 */
class Jikan
{

    /**
     * @var ClientInterface|Client
     */
    public static $guzzle;
    public $status = 200;
    public $response = [];

    /**
     * Jikan constructor.
     *
     * @param ClientInterface|null $client
     */
    public function __construct(ClientInterface $client = null)
    {
        self::$guzzle = $client ?? new Client([
            'http_errors' => false
        ]);
    }

    /**
     * Anime
     *
     * @param null  $id
     * @param array $extend
     *
     * @return $this
     */
    public function Anime($id = null, Array $extend = [])
    {
        $this->response = (array)(new Get\Anime($id, $extend))->response;
        $this->setStatus();

        return $this;
    }

    private function setStatus()
    {
        $this->status = $this->response['code'];
        unset($this->response['code']);
    }

    /**
     * Manga
     *
     * @param null  $id
     * @param array $extend
     *
     * @return $this
     */
    public function Manga($id = null, Array $extend = [])
    {
        $this->response = (array)(new Get\Manga($id, $extend))->response;
        $this->setStatus();

        return $this;
    }

    /**
     * Character
     *
     * @param null  $id
     * @param array $extend
     *
     * @return $this
     */
    public function Character($id = null, Array $extend = [])
    {
        $this->response = (array)(new Get\Character($id, $extend))->response;
        $this->setStatus();

        return $this;
    }

    /**
     * Person
     *
     * @param null  $id
     * @param array $extend
     *
     * @return $this
     */
    public function Person($id = null, Array $extend = [])
    {
        $this->response = (array)(new Get\Person($id, $extend))->response;
        $this->setStatus();

        return $this;
    }

    /**
     * Search
     *
     * @param string|null       $query
     * @param string            $type
     * @param int               $page
     * @param SearchConfig|null $config
     *
     * @return $this
     */
    public function Search(string $query = null, string $type = ANIME, int $page = 1, SearchConfig $config = null)
    {
        $this->response = (array)(new Get\Search($query, $type, $page, $config))->response;
        $this->setStatus();

        return $this;
    }

    /**
     * Seasonal Anime
     *
     * @param string|null $season
     * @param int|null    $year
     *
     * @return $this
     */
    public function Seasonal(string $season = null, int $year = null)
    {
        $this->response = (array)(new Get\Seasonal($season, $year))->response;
        $this->setStatus();

        return $this;
    }

    /**
     * Anime Schedule For Current Season
     *
     * @return $this
     */
    public function Schedule()
    {
        $this->response = (array)(new Get\Schedule())->response;
        $this->setStatus();

        return $this;
    }

    /**
     * Top Anime/Manga
     *
     * @param string      $type
     * @param int         $page
     * @param string|null $subtype
     *
     * @return $this
     */
    public function Top(string $type, int $page, string $subtype = null)
    {
        $this->response = (array)(new Get\Top($type, $page, $subtype))->response;
        $this->setStatus();

        return $this;
    }

    /*
     * User List
     *
     */
    /*    public function UserList(string $username, string $type, int $status = null) {
            $this->response = (array) (new Get\User($username, $type, $status))->response;
            $this->setStatus();

            return $this;
        }*/
}
