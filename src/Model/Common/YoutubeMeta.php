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
     * @var string|null
     */
    private $youtubeId;

    /**
     * @var string|null
     */
    private $url;

    /**
     * @var string|null
     */
    private $embedUrl;

    /**
     * @var YoutubeImageResource
     */
    private $images;

    /**
     * @param string $embedUrl
     * @return YoutubeMeta
     */
    public static function factory(?string $embedUrl) : self
    {
        $instance = new self;

        $instance->embedUrl = $embedUrl;
        $instance->youtubeId = Media::youtubeIdFromUrl($embedUrl);
        $instance->url = Media::generateYoutubeUrlFromId($instance->youtubeId);
        $instance->images = Media::generateYoutubeImageResource($instance->youtubeId);

        return $instance;
    }

    /**
     * @return string|null
     */
    public function getYoutubeId(): ?string
    {
        return $this->youtubeId;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return string|null
     */
    public function getEmbedUrl(): ?string
    {
        return $this->embedUrl;
    }

    /**
     * @return YoutubeImageResource
     */
    public function getImages(): YoutubeImageResource
    {
        return $this->images;
    }
}
