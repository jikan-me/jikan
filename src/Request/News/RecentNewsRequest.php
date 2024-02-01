<?php

namespace Jikan\Request\News;

use Jikan\Request\RequestInterface;

/**
 * Class RecentNewsRequest
 *
 * @package Jikan\Request
 */
class RecentNewsRequest implements RequestInterface
{
    /**
     * @var int
     */
    private int $page;

    /**
     * AnimeRequest constructor.
     *
     * @param int $page
     */
    public function __construct(int $page)
    {
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/news?p=%d', $this->page);
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

}
