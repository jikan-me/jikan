<?php

namespace Jikan\Model\Resource\ClubImageResource;

/**
 * Class Jpg
 * @package Jikan\Model\Resource\ClubImageResource
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

        if ($instance->imageUrl === null) {
            return $instance;
        }

        return $instance;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }
}
