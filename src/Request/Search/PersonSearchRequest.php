<?php

namespace Jikan\Request\Search;

use Jikan\Request\RequestInterface;

/**
 * Class PersonSearchRequest
 *
 *
 * @package Jikan\Request\Search
 */
class PersonSearchRequest implements RequestInterface
{

    /**
     * @var string
     */
    private $query;

    /**
     * @var int
     */
    private $page;

    /**
     * Advanced Search
     */

    /**
     * @var string
     */
    private $char;

    /**
     * PersonSearchRequest constructor.
     *
     * @param string $query
     * @param int    $page
     */
    public function __construct(string $query, int $page = 1)
    {
        $this->query = $query;
        $this->page = $page;
    }

    /**
     * Get the path to request
     *
     * @return string
     */
    public function getPath(): string
    {

        $query = http_build_query(
            [
                'q'      => $this->query,
                'show'   => ($this->page !== 1) ? 50 * ($this->page - 1) : null,
                'letter' => $this->char,
            ]
        );

        return sprintf(
            'https://myanimelist.net/character.php?%s',
            $query
        );
    }

    /**
     * @param string $char
     *
     * @return $this
     */
    public function setStartsWithChar(string $char): self
    {
        $this->char = $char;

        return $this;
    }
}
