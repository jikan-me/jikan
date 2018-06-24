<?php

namespace Jikan\Helper;

/**
 * Class Utils
 *
 * @package Jikan\Helper
 */
class Utils
{

    /**
     * @param $url
     *
     * @return bool
     */
    public static function isURL($url): bool
    {
        // return (filter_var($this->filePath, FILTER_VALIDATE_URL) ? true : false);
        return preg_match('`^http(s)?://`', $url) ? true : false;
    }

    /**
     * @param $status
     *
     * @return bool
     */
    public static function existsURL($status): bool
    {
        return ($status == 200 || $status == 303);
    }

    /**
     * @param $url
     *
     * @return bool|string
     */
    public static function getStatus($url)
    {
        return substr(get_headers($url)[0], 9, 3);
    }

    /**
     * @param $item
     * @param $key
     */
    public static function trim(&$item, $key)
    {
        $item = trim($item);
    }
}
