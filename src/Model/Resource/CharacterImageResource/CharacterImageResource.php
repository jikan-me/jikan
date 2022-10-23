<?php

namespace Jikan\Model\Resource\CharacterImageResource;

/**
 * Class CharacterImageResource
 * @package Jikan\Model\Resource\CharacterImageResource
 */
class CharacterImageResource
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
     * @return CharacterImageResource
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
     * @return Webp
     */
    public function getWebp(): Webp
    {
        return $this->webp;
    }

    /**
     * @return Jpg
     */
    public function getJpg(): Jpg
    {
        return $this->jpg;
    }
}
