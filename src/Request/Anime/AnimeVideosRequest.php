<?php

namespace Jikan\Request\Anime;

use Jikan\Request\RequestInterface;

/**
 * Class AnimeVideos
 *
 * @package Jikan\Request
 */
class AnimeVideosRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * AnimeVideosRequest constructor.
     *
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/anime/%d/_/video', $this->id);
    }
}
