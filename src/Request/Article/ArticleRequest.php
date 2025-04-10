<?php

namespace Jikan\Request\Article;

use Jikan\Request\RequestInterface;

/**
 * Class ArticleRequest
 *
 * @package Jikan\Request
 */
class ArticleRequest implements RequestInterface
{
    /**
     * @var int
     */
    private int $malId;

    /**
     * @param int $malId
     */
    public function __construct(int $malId)
    {
        $this->malId = $malId;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/featured/%d', $this->malId);
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        return $this->malId;
    }
}
