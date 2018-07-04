<?php

namespace Jikan\Request;

/**
 * Class AnimeEpisodes
 *
 * @package Jikan\Request
 */
class AnimeEpisodes implements RequestInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $page;

    /**
     * AnimeEpisodes constructor.
     *
     * @param int $id
     * @param int $page
     *
     */
    public function __construct(int $id, int $page = 1)
    {
        $this->id = $id;
        $this->page = ($page - 1) * 100;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/anime/%s/_/episode?offset=%s', $this->id, $this->page);
    }
}
