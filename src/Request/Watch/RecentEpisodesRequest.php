<?php

namespace Jikan\Request\Watch;

use Jikan\Request\RequestInterface;

/**
 * Class RecentEpisodesRequest
 *
 * @package Jikan\Request
 */
class RecentEpisodesRequest implements RequestInterface
{
    /**
     * @return string
     */
    public function getPath(): string
    {
        return 'https://myanimelist.net/watch/episode';
    }
}
