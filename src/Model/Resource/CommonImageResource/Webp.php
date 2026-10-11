<?php

namespace Jikan\Model\Resource\CommonImageResource;

/**
 * Class Webp
 * @package Jikan\Model\Resource\CommonImageResource
 */
class Webp
{
    /**
     * @var string|null
     */
    private ?string $imageUrl;

    /**
     * @var string|null
     */
    private ?string $smallImageUrl;

    /**
     * @var string|null
     */
    private ?string $largeImageUrl;


    /**
     * @param string $imageUrl
     * @return Jpg
     */
    public static function factory(?string $imageUrl): self
    {
        $instance = new self();

        $instance->imageUrl = $imageUrl === null ? null : str_replace('.jpg', '.webp', $imageUrl);
        $instance->smallImageUrl = $imageUrl === null ? null : str_replace('.jpg', 't.webp', $imageUrl);
        $instance->largeImageUrl = $imageUrl === null ? null : str_replace('.jpg', 'l.webp', $imageUrl);

        return $instance;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @return string|null
     */
    public function getSmallImageUrl(): ?string
    {
        return $this->smallImageUrl;
    }

    /**
     * @return string|null
     */
    public function getLargeImageUrl(): ?string
    {
        return $this->largeImageUrl;
    }
}
