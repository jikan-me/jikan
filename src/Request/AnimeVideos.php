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
        return sprintf('https://myanimelist.net/anime/%d/_/video', $this->id);
    }
}
