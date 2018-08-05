<?php

namespace Jikan\Request\Search;

use Jikan\Request\RequestInterface;

/**
 * Class CharacterSearchRequest
 *
 *
 * @package Jikan\Request\Search
 */
class CharacterSearchRequest implements RequestInterface
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
     * CharacterSearchRequest constructor.
     *
     * @param string|null $query
     * @param int    $page
     */
    public function __construct(?string $query = null, int $page = 1)
    {
        $this->query = $query;
        $this->page = $page;

        $this->query = $this->query ?? "";
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
     * @param null|string $query
     *
     * @return $this
     */
    public function setQuery(?string $query = null): self
    {
        $this->query = $query;
        $this->query = $this->query ?? "";

        return $this;
    }

    /**
     * @param int $page
     *
     * @return $this
     */
    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
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
