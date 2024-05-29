<?php

namespace Jikan\Model\Resource\WrapImageResource;

/**
 * Class Jpg
 * @package Jikan\Model\Resource\WrapImageResource
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
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }
}
