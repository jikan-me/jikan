<?php

namespace Jikan\Request\Anime;

use Jikan\Request\RequestInterface;

/**
 * Class AnimeEpisodeRequest
 *
 * @package Jikan\Request
 */
class AnimeEpisodeRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $episodeId;

    /**
     * AnimeEpisodeRequest constructor.
     *
     * @param int $id
     * @param int $page
     */
    public function __construct(int $id, int $episodeId)
    {
        $this->id = $id;
        $this->episodeId = $episodeId;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/anime/%s/_/episode/%s', $this->id, $this->episodeId);
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
    public function getEpisodeId(): int
    {
        return $this->episodeId;
    }
}
