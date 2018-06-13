<?php
/**
*	Jikan - MyAnimeList Unofficial API
*	Developed by Irfan | irfan.dahir.co
*	
*	This is an unofficial MAL API that provides the features that the official one lacks.
*	Jikan scraps web pages through a modular method, parses the data you require from MAL and returns it back as a PHP/JSON array/object.
*   Jikan parses the data MAL web pages and returns it as a PHP Array
*	No authentication is needed for utilizing this library.
*
*	Jikan is NOT affiliated with MyAnimeList.
*   This library does not perform any rate limitations, so use it responsibly.
*/

namespace Jikan;

require __DIR__ . '/config.php'; 


use Jikan\Helper\SearchConfig as SearchConfig;

class Jikan
{

    public $status = 200;
    public $response = [];

	public function __construct() {
		return $this;
	}

    private function setStatus() {
        $this->status = $this->response['code'];
        unset($this->response['code']);
    }

	/*
	 * Anime
	 */
	public function Anime($id = null, Array $extend = []) {
	    $this->response = (array) (new Get\Anime($id, $extend))->response;
        $this->setStatus();

	    return $this;
    }

    /*
     * Manga
     */
    public function Manga($id = null, Array $extend = []) {
        $this->response = (array) (new Get\Manga($id, $extend))->response;
        $this->setStatus();

        return $this;
    }

    /*
     * Character
     */
    public function Character($id = null, Array $extend = []) {
        $this->response = (array) (new Get\Character($id, $extend))->response;
        $this->setStatus();

        return $this;
    }

    /*
     * Person
     */
    public function Person($id = null, Array $extend = []) {
        $this->response = (array) (new Get\Person($id, $extend))->response;
        $this->setStatus();

        return $this;
    }

    /*
     * Search
     */
    public function Search(string $query = null, string $type = ANIME, int $page = 1, SearchConfig $config = null) {
        $this->response = (array) (new Get\Search($query, $type, $page, $config))->response;
        $this->setStatus();

        return $this;
    }

    /*
     * Seasonal Anime
     */
    public function Seasonal(string $season = null, int $year = null) {
        $this->response = (array) (new Get\Seasonal($season, $year))->response;
        $this->setStatus();

        return $this;
    }

    /*
     * Anime Schedule For Current Season
     */
    public function Schedule() {
        $this->response = (array) (new Get\Schedule())->response;
        $this->setStatus();

        return $this;
    }

    /*
     * Top Anime/Manga
     */
    public function Top(string $type, int $page, string $subtype = null) {
        $this->response = (array) (new Get\Top($type, $page, $subtype))->response;
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