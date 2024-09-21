<?php

namespace Jikan\Request\Article;

use Jikan\Request\RequestInterface;

/**
 * Class ArticlesByTagRequest
 *
 * @package Jikan\Request
 */
class ArticlesByTagRequest implements RequestInterface
{
    /**
     * @var int
     */
    private int $page;

    /**
     * @var string
     */
    private string $malId;


    /**
     * @param string $malId
     * @param int $page
     */
    public function __construct(string $malId, int $page = 1)
    {
        $this->malId = $malId;
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/featured/tag/%s?p=%d', $this->malId, $this->page);
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
    public function getMalId(): string
    {
        return $this->malId;
    }


}
