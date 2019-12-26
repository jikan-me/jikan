<?php

namespace Jikan\Request\Genre;

use Jikan\Request\RequestInterface;

/**
 * Class AnimeGenreRequest
 *
 * @package Jikan\Request
 */
class AnimeGenresRequest implements RequestInterface
{
    /**
     * AnimeGenreRequest constructor.

     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/anime.php');
    }
}
