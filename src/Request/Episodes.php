<?php

namespace Jikan\Request;

/**
 * Class Episodes
 *
 * @package Jikan\Request
 */
class Episodes implements RequestInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $page;

    /**
     * Episodes constructor.
     *
     * @param int $id
     * @param int $page
     *
     */
    public function __construct(int $id, int $page = 1)
    {
        $this->id = $id;
        $this->page = ($page - 1) * 100;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/anime/%s/_/episode?offset=%s', $this->id, $this->page);
    }
}
