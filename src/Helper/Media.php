<?php

namespace Jikan\Helper;

use Jikan\Model\Resource\YoutubeImageResource;

/**
 * Class Parser
 *
 * @package Jikan\Helper
 */
class Media
{
    /**
     * @param string|null $url
     * @return string
     */
    public static function youtubeIdFromUrl(?string $url) : ?string
    {
        /**
         *  https://stackoverflow.com/a/17030234
         */

        if ($url === null) {
            return null;
        }

        preg_match(
            "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^\?&\"'>]{11})/",
            $url,
            $matches
        );

        return $matches[1] ?? null;
    }

    /**
     * @param string $id
     * @return string
     */
    public static function generateYoutubeUrlFromId(?string $id) : ?string
    {
        if ($id === null) {
            return null;
        }

        return sprintf('https://www.youtube.com/watch?v=%s', $id);
    }

    /**
     * @param string $id
     * @return YoutubeImageResource
     */
    public static function generateYoutubeImageResource(?string $id) : YoutubeImageResource
    {
        return YoutubeImageResource::factory($id);
    }
}
