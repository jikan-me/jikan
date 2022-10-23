<?php

namespace Jikan\Model\Anime;

use Jikan\Model\Common\Collection\Pagination;
use Jikan\Model\Common\Collection\Results;
use Jikan\Model\Reviews\Reviews;
use Jikan\Parser\Anime\EpisodesParser;

/**
 * Class Episodes
 *
 * @package Jikan\Model
 */
class Episodes extends Results implements Pagination
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
     * @param EpisodesParser $parser
     *
     * @return Episodes
     * @throws \InvalidArgumentException
     */
    public static function fromParser(EpisodesParser $parser): self
    {
        $instance = new self();
        $instance->results = $parser->getEpisodes();
        $instance->lastVisiblePage = $parser->getLastPage();
        $instance->hasNextPage = $parser->getHasNextPage();

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
