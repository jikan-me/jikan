<?php

namespace Jikan\Model\Resource;

/**
 * Class YoutubeImageResource
 * @package Jikan\Model\Resource
 */
class YoutubeImageResource
{

    /**
     * @var string|null
     */
    private $defaultImageUrl;

    /**
     * @var string|null
     */
    private $smallImageUrl;

    /**
     * @var string|null
     */
    private $mediumImageUrl;

    /**
     * @var string|null
     */
    private $largeImageUrl;

    /**
     * @var string|null
     */
    private $maximumImageUrl;


    /**
     * @param string $id
     * @return YoutubeImageResource
     */
    public static function factory(?string $id) : self
    {
        $instance = new self;

        if ($id !== null) {
            $instance->defaultImageUrl = sprintf('http://img.youtube.com/vi/%s/default.jpg', $id);
            $instance->smallImageUrl = sprintf('http://img.youtube.com/vi/%s/sddefault.jpg', $id);
            $instance->mediumImageUrl = sprintf('http://img.youtube.com/vi/%s/mqdefault.jpg', $id);
            $instance->largeImageUrl = sprintf('http://img.youtube.com/vi/%s/hqdefault.jpg', $id);
            $instance->maximumImageUrl = sprintf('http://img.youtube.com/vi/%s/maxresdefault.jpg', $id);
        }

        return $instance;
    }

    /**
     * @return string|null
     */
    public function getDefaultImageUrl(): ?string
    {
        return $this->defaultImageUrl;
    }

    /**
     * @return string|null
     */
    public function getSmallImageUrl(): ?string
    {
        return $this->smallImageUrl;
    }

    /**
     * @return string|null
     */
    public function getMediumImageUrl(): ?string
    {
        return $this->mediumImageUrl;
    }

    /**
     * @return string|null
     */
    public function getLargeImageUrl(): ?string
    {
        return $this->largeImageUrl;
    }

    /**
     * @return string|null
     */
    public function getMaximumImageUrl(): ?string
    {
        return $this->maximumImageUrl;
    }
}
