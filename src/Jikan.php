<?php
/**
*	Jikan - MyAnimeList Unofficial API @version 1.4.X stable
*	Developed by Nekomata | irfandahir.com
*	
*	This is an unofficial MAL API that provides the features that the official one lacks.
*	Jikan scraps web pages through a modular method, parses the data you require from MAL and returns it back as a PHP/JSON array/object.
*	Therefore, no authentication is needed for fetching anime, manga, character, people data.
*
*	Jikan is in no way affiliated with MyAnimeList.
*/
namespace Jikan;

require 'config.php';


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
	public function Anime(String $id = null, Array $extend = []) {
	    $this->response = (array) (new Get\Anime($id, $extend))->response;
        $this->setStatus();

	    return $this;
    }

    /*
     * Manga
     */
    public function Manga(String $id = null, Array $extend = []) {
        $this->response = (array) (new Get\Manga($id, $extend))->response;
        $this->setStatus();

        return $this;
    }

    /*
     * Character
     */
    public function Character(String $id = null, Array $extend = []) {
        $this->response = (array) (new Get\Character($id, $extend))->response;
        $this->setStatus();

        return $this;
    }

    /*
     * Person
     */
    public function Person(String $id = null, Array $extend = []) {
        $this->response = (array) (new Get\Person($id, $extend))->response;
        $this->setStatus();

        return $this;
    }

    /*
     * Search
     */
    public function Search(String $query = null, String $type = ANIME, $page = 1) {
        $this->response = (array) (new Get\Search($query, $type, $page))->response;
        $this->setStatus();

        return $this;
    }

}