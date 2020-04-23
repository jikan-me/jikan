<?php

namespace Jikan\Model\Search;

use Jikan\Model\Common\Collection\Pagination;
use Jikan\Model\Common\Collection\Results;
use Jikan\Parser;

/**
 * Class AnimeSearch
 *
 * @package Jikan\Model\Search\Search
 */
class AnimeSearchAlt extends Results implements Pagination
{

    /**
     * @var bool
     */
    private $hasNextPage;

    /**
     * @var int
     */
    private $lastVisiblePage;

    /**
     * @param Parser\Search\AnimeSearchParser $parser
     *
     * @return AnimeSearch
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\Search\AnimeSearchParser $parser): self
    {
        $instance = new self();

        $instance->results = $parser->getResults();
        $instance->hasNextPage = true;
        $instance->lastVisiblePage = 20;

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
}
