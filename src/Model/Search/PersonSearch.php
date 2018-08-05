<?php

namespace Jikan\Model\Search;

use Jikan\Parser;

/**
 * Class PersonSearch
 *
 * @package Jikan\Model\Search\Search
 */
class PersonSearch
{

    /**
     * @var PersonSearchListItem[]
     */
    private $results;

    /**
     * @var int
     */
    private $lastPage;


    /**
     * @param Parser\Search\PersonSearchParser $parser
     *
     * @return PersonSearch
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\Search\PersonSearchParser $parser): self
    {
        $instance = new self();

        $instance->results = $parser->getResults();
        $instance->lastPage = $parser->getLastPage();

        return $instance;
    }

    /**
     * @return PersonSearchListItem[]
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
