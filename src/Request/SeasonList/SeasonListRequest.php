<?php

namespace Jikan\Request\SeasonList;

use Jikan\Request\RequestInterface;

/**
 * Class SeasonListRequest
 *
 * @package Jikan\Request\SeasonListItem
 */
class SeasonListRequest implements RequestInterface
{
    /**
     * @return string
     */
    public function getPath(): string
    {
        return 'https://myanimelist.net/anime/season/archive';
    }
}
