<?php

namespace Jikan\Model\News;

use Jikan\Model\Common\Collection\Pagination;
use Jikan\Model\Common\Collection\Results;
use Jikan\Parser;

/**
 * Class ResourceNewsList
 *
 * @package Jikan\Model\News\ResourceNewsList
 */
class ResourceNewsList extends Results implements Pagination
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
     * @param Parser\News\ResourceNewsListParser $parser
     *
     * @return ResourceNewsList
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\News\ResourceNewsListParser $parser): self
    {
        $instance = new self();

        $instance->results = $parser->getResults();
        $instance->hasNextPage = $parser->getHasNextPage();

        return $instance;
    }

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
