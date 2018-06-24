<?php
/**
 *   Jikan - MyAnimeList Unofficial API v2
 *   Developed by Irfan | irfan.dahir.co
 *
 *   This is an unofficial MAL API that provides the features that the official one lacks.
 *   Jikan scraps web pages through a modular method, parses the data you require from MAL and returns it back as a
 * PHP/JSON array/object. Jikan parses the data MAL web pages and returns it as a PHP array No authentication is needed
 * for utilizing this library.
 *
 *   Jikan is NOT affiliated with MyAnimeList.
 *   This library does not perform any rate limitations, so use it responsibly.
 */

namespace Jikan;

use Jikan\Request\Anime;

require __DIR__.'/consts.php';


/**
 * Class Jikan
 *
 * @package Jikan
 */
class Jikan
{
    public $response;
    public $request;

    /**
     * Anime
     *
     * @param Anime $request
     *
     * @return $this
     */
    /**
     * @param Anime $request
     *
     * @return $this
     */
    public function Anime(Anime $request)
    {
        $this->request = $request;
        $this->response = new Get\Anime($this->request);

        return $this;
    }
}
