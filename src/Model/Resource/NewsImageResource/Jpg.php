<?php

namespace Jikan\Model\Resource\NewsImageResource;

/**
 * Class Jpg
 * @package Jikan\Model\Resource\NewsImageResource
 */
class Jpg
{
    /**
     * @var string|null
     */
    private ?string $imageUrl;

    /**
     * @var string|null
     */
    private ?string $smallImageUrl;

    /**
     * @var string|null
     */
    private ?string $largeImageUrl;


    /**
     * @param string|null $imageUrl
     * @return self
     */
    public static function factory(?string $imageUrl) : self
    {
        $instance = new self;

        $instance->imageUrl = $imageUrl;

        if ($instance->imageUrl === null) {
            return $instance;
        }

        $instance->smallImageUrl = str_replace('/s/', '/r/100x156/s/', $imageUrl);
        $instance->largeImageUrl = str_replace('/s/', '/r/200x312/s/', $imageUrl);

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
