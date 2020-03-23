<?php

namespace Jikan\Model\Search;

use Jikan\Parser;

/**
 * Class UserSearch
 *
 * @package Jikan\Model\Search\Search
 */
class UserSearch
{

    /**
     * @var UserSearchListItem[]
     */
    private $results = [];

    /**
     * @var int
     */
    private $lastPage = 1;

    /**
     * @param Parser\Search\UserSearchParser $parser
     *
     * @return UserSearch
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\Search\UserSearchParser $parser): self
    {
        $instance = new self();

        $instance->results = $parser->getResults();
        $instance->lastPage = $parser->getLastPage();

        return $instance;
    }

    /**
     * @return UserSearchListItem[]
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
