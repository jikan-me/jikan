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
                strip_tags(
                    str_replace(['<br>', '<br/>', '<br />'], '\n', $string)
                ),
                ENT_QUOTES
            )
        );
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function UTF8NbspTrim(string $string): string
    {
        return trim($string, \chr(0xC2).\chr(0xA0));
    }
}
