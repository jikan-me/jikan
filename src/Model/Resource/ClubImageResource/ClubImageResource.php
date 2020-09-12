<?php

namespace Jikan\Model\Resource\ClubImageResource;

/**
 * Class PersonImageResource
 * @package Jikan\Model\Resource\PersonImageResource
 */
class ClubImageResource
{

    /**
     * @var Jpg
     */
    private $jpg;


    /**
     * @param string $imageUrl
     * @return ClubImageResource
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
