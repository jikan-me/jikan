<?php
/**
*	Jikan - MyAnimeList Unofficial API v2
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

require __DIR__ . '/consts.php'; 

class Jikan
{
    public $response;
    public $request;

    /*
     * Anime
     */
    public function Anime(\Jikan\Request\Anime $request) {
        $this->request = $request;
        $this->response = (new \Jikan\Get\Anime($this->request))->response;

        return $this;
    }

    /*
     * Manga
     */
    public function Manga(\Jikan\Request\Manga $request) {
        $this->request = $request;
        $this->response = (new \Jikan\Get\Manga($this->request))->response;

        return $this;
    }


}