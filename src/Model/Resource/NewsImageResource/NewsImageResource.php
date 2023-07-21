<?php

namespace Jikan\Model\Resource\NewsImageResource;

use Jikan\Model\Resource\NewsImageResource\Jpg;

/**
 * Class NewsImageResource
 * @package Jikan\Model\Resource\NewsImageResource
 */
class NewsImageResource
{
    /**
     * @var Jpg
     */
    private Jpg $jpg;


    /**
     * @param string|null $imageUrl
     * @return NewsImageResource
     */
    public static function factory(?string $imageUrl) : NewsImageResource
    {
        $instance = new self;

        $instance->jpg = Jpg::factory($imageUrl);

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

}
