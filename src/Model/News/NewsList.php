<?php

namespace Jikan\Model\News;

use Jikan\Model\Common\Collection\Pagination;
use Jikan\Model\Common\Collection\Results;
use Jikan\Parser;

/**
 * Class NewsList
 */
class NewsList extends Results implements Pagination
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
     * @param Parser\News\NewsListParser $parser
     * @return static
     */
    public static function fromParser(Parser\News\NewsListParser $parser): self
    {
        $instance = new self();

        $instance->results = $parser->getResults();
        $instance->lastVisiblePage = $parser->getLastVisiblePage();
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
