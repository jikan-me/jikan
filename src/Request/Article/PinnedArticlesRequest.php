<?php

namespace Jikan\Request\Article;

use Jikan\Request\RequestInterface;

/**
 * Class RecentArticleRequest
 *
 * @package Jikan\Request
 */
class PinnedArticlesRequest implements RequestInterface
{
    /**
     * @return string
     */
    public function getPath(): string
    {
        return 'https://myanimelist.net/featured';
    }
}
