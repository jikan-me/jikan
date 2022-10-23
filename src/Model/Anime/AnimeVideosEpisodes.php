<?php

namespace Jikan\Model\Anime;

use Jikan\Model\Common\Collection\Pagination;
use Jikan\Model\Common\Collection\Results;
use Jikan\Parser;

/**
 * Class AnimeVideosEpisodes
 *
 * @package Jikan\Model\Anime\AnimeVideosEpisodes
 */
class AnimeVideosEpisodes extends Results implements Pagination
{
    /**
     * @var bool
     */
    private $hasNextPage = false;

    /**
     * @var int
     */
    private $lastVisiblePage = 1;


    /**
     * @param Parser\Anime\VideosParser $parser
     * @return static
     */
    public static function fromParser(Parser\Anime\VideosParser $parser): self
    {
        $instance = new self();

        $instance->results = $parser->getEpisodes();
        $instance->hasNextPage = $parser->getHasNextPage();
        $instance->lastVisiblePage = $parser->getLastPage();

        return $instance;
    }

    /**
     * @return static
     */
    public static function mock() : self
    {
        return new self();
    }

    /**
     * @return bool
     */
    public function hasNextPage(): bool
    {
        return $this->hasNextPage;
    }

    /**
     * @return int
     */
    public function getLastVisiblePage(): int
    {
        return $this->lastVisiblePage;
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }
}
