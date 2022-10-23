<?php

namespace Jikan\Request\Watch;

use Jikan\Request\RequestInterface;

/**
 * Class PopularEpisodesRequest
 *
 * @package Jikan\Request
 */
class PopularEpisodesRequest implements RequestInterface
{
    /**
     * @return string
     */
    public function getPath(): string
    {
        return 'https://myanimelist.net/watch/episode/popular';
    }
}
