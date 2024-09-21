<?php

namespace Jikan\Request\Article;

use Jikan\Request\RequestInterface;

/**
 * Class RecentArticleRequest
 *
 * @package Jikan\Request
 */
class RecentArticleRequest implements RequestInterface
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
    public function __construct(int $page = 1)
    {
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/featured?p=%d', $this->page);
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

}
