<?php

namespace Jikan\Request\Schedule;

use Jikan\Request\RequestInterface;

/**
 * Class Schedule
 *
 * @package Jikan\Request
 */
class ScheduleRequest
{
    /**
     * @return string
     */
    public function getPath(): string
    {
        return 'https://myanimelist.net/anime/season/schedule';
    }
}
