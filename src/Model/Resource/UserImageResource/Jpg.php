<?php

namespace Jikan\Model\Resource\UserImageResource;

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
     * @param string $imageUrl
     * @return Jpg
     */
    public static function factory(?string $imageUrl) : self
    {
        $instance = new self;

        $instance->imageUrl = $imageUrl;

        return $instance;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->getJpg()->getImageUrl();
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }
}
