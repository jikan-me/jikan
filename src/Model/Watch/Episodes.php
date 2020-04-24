<?php

namespace Jikan\Model\Watch;

use Jikan\Model\Common\Collection\Pagination;
use Jikan\Model\Common\Collection\Results;
use Jikan\Parser\Watch\WatchEpisodesParser;

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
     * @param WatchEpisodesParser $parser
     *
     * @return Episodes
     * @throws \Exception
     */
    public static function fromParser(WatchEpisodesParser $parser): Episodes
    {
        $instance = new self();
        $instance->results = $parser->getResults();

        return $instance;
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
