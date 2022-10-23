<?php

namespace Jikan\Request\Watch;

use Jikan\Request\RequestInterface;

/**
 * Class PopularPromotionalVideosRequest
 *
 * @package Jikan\Request
 */
class PopularPromotionalVideosRequest implements RequestInterface
{
    /**
     * @return string
     */
    public function getPath(): string
    {
        return 'https://myanimelist.net/watch/promotion/popular';
    }
}
