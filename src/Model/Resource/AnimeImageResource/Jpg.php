<?php

namespace Jikan\Model\Resource\AnimeImageResource;

/**
 * Class Jpg
 * @package Jikan\Model\Resource\CommonImageResource
 */
class Jpg
{
    /**
     * @var string|null
     */
    private $imageUrl;

    /**
     * @var string|null
     */
    private $smallImageUrl;

    /**
     * @var string|null
     */
    private $largeImageUrl;


    /**
     * @param string $imageUrl
     * @return Jpg
     */
    public static function factory(?string $imageUrl) : self
    {
        $instance = new self;

        $instance->imageUrl = $imageUrl;

        if ($instance->imageUrl === null) {
            return $instance;
        }

        $instance->smallImageUrl = str_replace('.jpg', 't.jpg', $imageUrl);
        $instance->largeImageUrl = str_replace('.jpg', 'l.jpg', $imageUrl);

        return $instance;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
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
    public function getLargeImageUrl(): ?string
    {
        return $this->largeImageUrl;
    }
}
