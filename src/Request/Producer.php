<?php

namespace Jikan\Request;

class Producer implements RequestInterface
{
    /**
     * @var int
     */
    private $page;

    /**
     * Producer constructor.
     *
     * @param int    $page
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(int $page = 1)
    {
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/anime/producer/%s', $this->page);
    }
}
