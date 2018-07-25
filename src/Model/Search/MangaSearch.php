<?php

namespace Jikan\Model\Search;

use Jikan\Parser;

/**
 * Class MangaSearch
 *
 * @package Jikan\Model\Search\Search
 */
class MangaSearch
{

    /**
     * @var MangaSearchListItem[]
     */
    private $results;

    /**
     * @var int
     */
    private $lastPage;


    /**
     * @param Parser\Search\MangaSearchParser $parser
     *
     * @return MangaSearch
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\Search\MangaSearchParser $parser): self
    {
        $instance = new self();

        $instance->results = $parser->getResults();
        $instance->lastPage = $parser->getLastPage();

        return $instance;
    }

    /**
     * @return MangaSearchListItem[]
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @return int
     */
    public function getLastPage(): int
    {
        return $this->lastPage;
    }
}
