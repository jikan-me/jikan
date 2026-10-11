<?php

namespace Jikan\Model\Resource\AnimeImageResource;

use Jikan\Helper\ImagePlaceholder;

/**
 * Class AnimeImageResource
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
     * @param string|null $imageUrl
     * @return AnimeImageResource
     */
    public static function factory(?string $imageUrl): self
    {
        $imageUrl = ImagePlaceholder::normalize($imageUrl);

        $instance = new self();

        $instance->jpg = Jpg::factory($imageUrl);
        $instance->webp = Webp::factory($imageUrl);

        return $instance;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getJpg()->getImageUrl() ?? '';
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
