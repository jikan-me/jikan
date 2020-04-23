<?php

namespace Jikan\Model\Watch;

use Jikan\Model\Common\Collection\Pagination;
use Jikan\Model\Common\Collection\Results;
use Jikan\Parser\Watch\WatchPromotionalVideosParser;

/**
 * Class PromotionalVideos
 *
 * @package Jikan\Model
 */
class PromotionalVideos extends Results implements Pagination
{
    /**
     * @param WatchPromotionalVideosParser $parser
     *
     * @return PromotionalVideos
     * @throws \Exception
     */
    public static function fromParser(WatchPromotionalVideosParser $parser): PromotionalVideos
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
     * @return int|null
     */
    public function getLastVisiblePage(): ?int
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
