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

    public $response = [];

	public function __construct() {
		return $this;
	}

	/*
	 * Anime
	 */
	public function Anime(String $id = null, Array $extend = []) {
	    $this->response = (array) (new Get\Anime($id, $extend))->response;

	    return $this;
    }

    /*
     * Manga
     */
    public function Manga(String $id = null, Array $extend = []) {
        $this->response = (array) (new Get\Manga($id, $extend))->response;

        return $this;
    }

    /*
     * Character
     */
    public function Character(String $id = null, Array $extend = []) {
        $this->response = (array) (new Get\Character($id, $extend))->response;

        return $this;
    }

    /*
     * Person
     */
    public function Person(String $id = null, Array $extend = []) {
        $this->response = (array) (new Get\Person($id, $extend))->response;

        return $this;
    }

    /*
     * Search
     */
    public function Search(String $query = null, String $type = ANIME, Array $extend = []) {

        return $this;
    }

}