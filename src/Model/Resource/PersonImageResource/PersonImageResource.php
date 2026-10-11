<?php

namespace Jikan\Model\Resource\PersonImageResource;

use Jikan\Helper\ImagePlaceholder;

/**
 * Class PersonImageResource
 * @package Jikan\Model\Resource\PersonImageResource
 */
class PersonImageResource
{
    /**
     * @var Jpg
     */
    private $jpg;


    /**
     * @param string $imageUrl
     * @return PersonImageResource
     */
    public static function factory(?string $imageUrl): self
    {
        $imageUrl = ImagePlaceholder::normalize($imageUrl);

        $instance = new self();

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
