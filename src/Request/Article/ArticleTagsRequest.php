<?php

namespace Jikan\Request\Article;

use Jikan\Request\RequestInterface;

/**
 * Class ArticleTagsRequest
 *
 * @package Jikan\Request
 */
class ArticleTagsRequest implements RequestInterface
{
    /**
     * @return string
     */
    public function getPath(): string
    {
        return 'https://myanimelist.net/featured/tag';
    }
}
