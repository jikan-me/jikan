<?php

namespace Jikan\Helper;

/**
 * Class JString
 *
 * @package Jikan\Helper
 */
class JString
{

    /**
     * @param string $string
     *
     * @return string
     */
    public static function cleanse(string $string): string
    {
        return trim(
            htmlspecialchars_decode(
                strip_tags($string),
                ENT_QUOTES
            )
        );
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function utf8(string $string): string
    {
        return utf8_encode($string);
    }

    /**
     * @param string $string
     *
     * @return null|string
     */
    public static function assertNull(string $string)
    {
        return empty($string) ? null : $string;
    }

}