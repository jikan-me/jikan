<?php

namespace Jikan\Model\Common;

use Jikan\Helper\Media;
use Jikan\Model\Resource\YoutubeImageResource;

/**
 * Class YoutubeMeta
 *
 * @package Jikan\Model
 */
class YoutubeMeta
{
    /**
     * @var string
     */
    private $youtubeId;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $embedUrl;

    /**
     * @var YoutubeImageResource
     */
    private $imageResource;

    /**
     * @param string $embedUrl
     * @return YoutubeMeta
     */
    public static function factory(string $embedUrl) : self
    {
        $instance = new self;

        $instance->embedUrl = $embedUrl;
        $instance->youtubeId = Media::youtubeIdFromUrl($embedUrl);
        $instance->url = Media::generateYoutubeUrlFromId($instance->youtubeId);
        $instance->imageResource = Media::generateYoutubeImageResource($instance->youtubeId);

        return $instance;
    }

    /**
     * @return string
     */
    public function getYoutubeId(): string
    {
        return $this->youtubeId;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getEmbedUrl(): string
    {
        return $this->embedUrl;
    }

    /**
     * @return YoutubeImageResource
     */
    public function getImageResource(): YoutubeImageResource
    {
        return $this->imageResource;
    }
}
