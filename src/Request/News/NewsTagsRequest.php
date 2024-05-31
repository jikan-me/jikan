<?php

namespace Jikan\Request\News;

use Jikan\Request\RequestInterface;

/**
 * Class NewsTagsRequest
 *
 * @package Jikan\Request
 */
class NewsTagsRequest implements RequestInterface
{
    /**
     * @return string
     */
    public function getPath(): string
    {
        return 'https://myanimelist.net/news/tag';
    }
}
