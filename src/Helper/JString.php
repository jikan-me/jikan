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
     * @description Cleanse HTML into JSON string
     * @return string
     */
    public static function cleanse(string $string): string
    {
        // remove control characters
        $string = preg_replace('~[[:cntrl:]]~', "", $string);

        // strip any leftover tags
        $string = strip_tags($string);

        // trim
        $string = str_replace('\\n', "\n", $string);
        $string = trim($string);

        return $string;
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

    /**
     * @param  string $string
     * @return string
     */
    public static function strToCanonical(string $string) : string
    {
        return str_replace(
            [' ', '/'],
            '_',
            preg_replace("/[^[:alnum:][:space:]\-\/]/u", '', $string)
        );
    }
}
