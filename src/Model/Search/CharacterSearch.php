<?php

namespace Jikan\Model\Search;

use Jikan\Parser;

/**
 * Class CharacterSearch
 *
 * @package Jikan\Model\Search
 */
class CharacterSearch
{

    /**
     * @var CharacterSearchItem[]
     */
    private $results;

    /**
     * @var int
     */
    private $lastPage;


    /**
     * @param Parser\Search\CharacterSearchParser $parser
     *
     * @return CharacterSearch
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\Search\CharacterSearchParser $parser): self
    {
        $instance = new self();

        $instance->results = $parser->getResults();
        $instance->lastPage = $parser->getLastPage();

        return $instance;
    }

    /**
     * @return CharacterSearchItem[]
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
