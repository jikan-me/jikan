<?php

namespace Jikan\Model\Common\Collection;

/**
 * Class Results
 *
 * @package Jikan\Model
 */
abstract class Results
{
    /**
     * @var array
     */
    protected array $results = [];

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @param array $results
     * @return Results
     */
    public function setResults(array $results): Results
    {
        $this->results = $results;
        return $this;
    }
}
