<?php

namespace Jikan\Request;

/**
 * Class AnimeVideos
 *
 * @package Jikan\Request
 */
class AnimeVideos implements RequestInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * AnimeRequest constructor.
     *
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        // MyAnimeList wants <something> after /<id>/... it happily accepts jikan as a valid parameter though
        return sprintf('https://myanimelist.net/anime/%d/jikan/video', $this->id);
    }
}
