<?php

namespace Jikan\Model\Recommendations;

use Jikan\Model\Common\Collection\Pagination;
use Jikan\Model\Common\Collection\Results;
use Jikan\Parser;

/**
 * Class RecentNews
 */
class RecentNews extends Results implements Pagination
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
     * @param Parser\News\RecentNewsParser $parser
     * @return static
     */
    public static function fromParser(Parser\News\RecentNewsParser $parser): self
    {
        $instance = new self();

        $instance->results = $parser->getRecentNews();
        $instance->lastVisiblePage = $parser->getLastPage();
        $instance->hasNextPage = $parser->hasNextPage();

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
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
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
}
