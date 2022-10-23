<?php

namespace Jikan\Model\Resource\AnimeImageResource;

/**
 * Class CommonImageResource
 * @package Jikan\Model\Resource\CommonImageResource
 */
class AnimeImageResource
{
    /**
     * @var Jpg
     */
    private $jpg;

    /**
     * @var Webp
     */
    private $webp;

    /**
     * @param string $imageUrl
     * @return AnimeImageResource
     */
    public static function factory(?string $imageUrl) : self
    {
        $instance = new self;

        $instance->jpg = Jpg::factory($imageUrl);
        $instance->webp = Webp::factory($imageUrl);

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
     * @return Jpg
     */
    public function getJpg(): Jpg
    {
        return $this->jpg;
    }

    /**
     * @return Webp
     */
    public function getWebp(): Webp
    {
        return $this->webp;
    }
}
