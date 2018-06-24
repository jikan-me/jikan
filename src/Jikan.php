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
use Jikan\Model\Anime;
use Jikan\MyAnimeList\MalClient;
use Jikan\Request\AnimeRequest;

require __DIR__.'/consts.php';

/**
 * Class Jikan
 *
 * @package Jikan
 */
class Jikan
{
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
     * @param Request\AnimeRequest $request
     *
     * @return Anime
     */
    public function Anime(AnimeRequest $request): Anime
    {
        return $this->myanimelist->getAnime($request);
    }

    /*
     * Manga
     */
    public function Manga(\Jikan\Request\Manga $request)
    {
        $this->request = $request;
        $this->response = (new \Jikan\Get\Manga($this->request))->response;

        return $this;
    }


}