<?php

namespace Jikan\Model\Resource\UserImageResource;

/**
 * Class CommonImageResource
 * @package Jikan\Model\Resource\UserImageResource
 */
class UserImageResource
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
     * @return UserImageResource
     */
    public static function factory(?string $imageUrl) : self
    {
        $instance = new self;

        $instance->jpg = Jpg::factory($imageUrl);
        $instance->webp = Webp::factory($imageUrl);

        return $instance;
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
