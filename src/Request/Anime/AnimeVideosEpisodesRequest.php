<?php

namespace Jikan\Request\Anime;

use Jikan\Request\RequestInterface;

/**
 * Class AnimeVideosEpisodesRequest
 *
 * @package Jikan\Request
 */
class AnimeVideosEpisodesRequest implements RequestInterface
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var int
     */
    private int $page;

    /**
     * AnimeVideosEpisodesRequest constructor.
     *
     * @param int $id
     */
    public function __construct(int $id, int $page)
    {
        $this->id = $id;
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/anime/%d/_/video?p=%d', $this->id, $this->page);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }
}
