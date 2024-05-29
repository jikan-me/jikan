<?php

namespace Jikan\Model\Resource\UserImageResource;

/**
 * Class Webp
 * @package Jikan\Model\Resource\CommonImageResource
 */
class Webp
{
    /**
     * @var string|null
     */
    private $imageUrl;

    /**
     * @param string $imageUrl
     * @return \Jikan\Model\Resource\AnimeImageResource\Webp
     */
    public static function factory(?string $imageUrl) : self
    {
        $instance = new self;

        $instance->imageUrl = str_replace('.jpg', '.webp', $imageUrl);

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
