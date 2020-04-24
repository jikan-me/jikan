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
     * @param string $url
     * @return string
     */
    public static function youtubeIdFromUrl(string $url) : string
    {
        /**
         *  https://gist.github.com/ghalusa/6c7f3a00fd2383e5ef33
         *  Here is a sample of the URLs this regex matches:
         * (there can be more content after the given URL that will be ignored)
         *  http://youtu.be/dQw4w9WgXcQ
         *  http://www.youtube.com/embed/dQw4w9WgXcQ
         *  http://www.youtube.com/watch?v=dQw4w9WgXcQ
         *  http://www.youtube.com/?v=dQw4w9WgXcQ
         *  http://www.youtube.com/v/dQw4w9WgXcQ
         *  http://www.youtube.com/e/dQw4w9WgXcQ
         *  http://www.youtube.com/user/username#p/u/11/dQw4w9WgXcQ
         *  http://www.youtube.com/sandalsResorts#p/c/54B8C800269D7C1B/0/dQw4w9WgXcQ
         *  http://www.youtube.com/watch?feature=player_embedded&v=dQw4w9WgXcQ
         *  http://www.youtube.com/?feature=player_embedded&v=dQw4w9WgXcQ
         *  It also works on the youtube-nocookie.com URL with the same above options.
         *  It will also pull the ID from the URL in an embed code (both iframe and object tags)
         */
        preg_match(
            '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
            $url,
            $match
        );

        return $match[1];
    }

    /**
     * @param string $id
     * @return string
     */
    public static function generateYoutubeUrlFromId(string $id) : string
    {
        return sprintf('https://www.youtube.com/watch?v=%s', $id);
    }

    /**
     * @param string $id
     * @return YoutubeImageResource
     */
    public static function generateYoutubeImageResource(string $id) : YoutubeImageResource
    {
        return YoutubeImageResource::factory($id);
    }
}
