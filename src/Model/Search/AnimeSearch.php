<?php

namespace Jikan\Model\Search;

use Jikan\Parser;

/**
 * Class AnimeSearch
 *
 * @package Jikan\Model\Search\Search
 */
class AnimeSearch
{

    /**
     * @var AnimeSearchListItem[]
     */
    private $results;

    /**
     * @var int
     */
    private $lastPage;


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
        $instance->lastPage = $parser->getLastPage();

        return $instance;
    }

    /**
     * @return AnimeSearchListItem[]
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
