<?php

namespace Jikan\Request;

/**
 * Class Magazine
 *
 * @package Jikan\Request
 */
class Magazine implements RequestInterface
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
     * Magazine constructor.
     *
     * @param int $id
     * @param int $page
     *
     */
    public function __construct(int $id, int $page = 1)
    {
        $this->id = $id;
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/manga/magazine/%s?page=%s', $this->id, $this->page);
    }
}
