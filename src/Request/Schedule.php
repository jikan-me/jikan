<?php

namespace Jikan\Request;

/**
 * Class Schedule
 *
 * @package Jikan\Request
 */
class Schedule
{
    /**
     * @return string
     */
    public function getPath(): string
    {
        return 'https://myanimelist.net/anime/season/schedule';
    }
}
