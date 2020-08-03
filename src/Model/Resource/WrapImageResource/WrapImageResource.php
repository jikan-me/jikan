<?php

namespace Jikan\Model\Resource\WrapImageResource;

/**
 * Class WrapImageResource
 * @package Jikan\Model\Resource\WrapImageResource
 */
class WrapImageResource
{

    /**
     * @var Jpg
     */
    private $jpg;

    /**
     * @param string $imageUrl
     * @return WrapImageResource
     */
    public static function factory(?string $imageUrl) : self
    {
        $instance = new self;

        $instance->jpg = Jpg::factory($imageUrl);

        return $instance;
    }

    /**
     * @return Jpg
     */
    public function getJpg(): Jpg
    {
        return $this->jpg;
    }
}
