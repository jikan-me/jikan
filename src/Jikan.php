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
use Jikan\MyAnimeList\MalClient;
use Jikan\Model;
use Jikan\Request;

require __DIR__.'/consts.php';

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
     */
    public function Anime(Request\Anime $request): Model\Anime
    {
        return $this->myanimelist->getAnime($request);
    }

    /**
     * @param Request\Manga $request
     *
     * @return Model\Manga
     */
    public function Manga(Request\Manga $request): Model\Manga
    {
        //return $this->myanimelist->getManga($request);
    }
}
