<?php

namespace Jikan\Request\Search;

use Jikan\Request\RequestInterface;

/**
 * Class ArticleSearchRequest
 *
 * @package Jikan\Request
 */
class ArticleSearchRequest implements RequestInterface
{
    /**
     * @var int
     */
    private int $page;

    /**
     * @var string
     */
    private string $query;


    /**
     * @param string $query
     * @param int $page
     */
    public function __construct(string $query, int $page = 1)
    {
        $this->query = $query;
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/featured/search?q=%s&p=%d', $this->query, $this->page);
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

}
