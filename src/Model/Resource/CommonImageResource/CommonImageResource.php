<?php

namespace Jikan\Model\Resource\CommonImageResource;

use Jikan\Helper\ImagePlaceholder;

/**
 * Class CommonImageResource
 * @package Jikan\Model\Resource\CommonImageResource
 */
class CommonImageResource
{
    /**
     * @var Jpg
     */
    private Jpg $jpg;

    /**
     * @var Webp
     */
    private Webp $webp;

    /**
     * @param string|null $imageUrl
     * @return CommonImageResource
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
