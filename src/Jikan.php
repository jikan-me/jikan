<?php
/**
*	Jikan - MyAnimeList Unofficial API @version 1.0.0 beta
*	Developed by Nekomata | irfandahir.com
*	
*	This is an unofficial MAL API that provides the features that the official one lacks.
*	Jikan scraps web pages through a modular method, parses the data you require from them and returns it back as a PHP/JSON array.
*	Therefore, no authentication is needed for fetching anime, manga, character, people, search result data.
*
*	Jikan is in no way affiliated with MyAnimeList.
*/
namespace Jikan;

require 'config.php';


class Jikan
{

	public function __construct() {

		return $this;
	}

	public function Anime($id) {
	    return new Get\Anime($id);
    }

    public function Manga($id) {

    }

    public function Character($id) {

    }

    public function Person($id) {

    }

    public function UserList($id) {

    }


}