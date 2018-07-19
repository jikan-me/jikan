<?php

namespace Jikan\Request\Schedule;

use Jikan\Request\RequestInterface;

/**
 * Class ScheduleRequest
 *
 * @package Jikan\Request
 */
class ScheduleRequest implements RequestInterface
{
    /**
     * @return string
     */
    public function getPath(): string
    {
        return 'https://myanimelist.net/anime/season/schedule';
    }
}
