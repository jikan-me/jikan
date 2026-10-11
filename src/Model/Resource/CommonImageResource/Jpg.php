<?php

namespace Jikan\Model\Resource\CommonImageResource;

/**
 * Class Jpg
 * @package Jikan\Model\Resource\CommonImageResource
 */
class Jpg
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
     * @param string|null $imageUrl
     * @return Jpg
     */
    public static function factory(?string $imageUrl): self
    {
        $instance = new self();

        $instance->imageUrl = $imageUrl;
        $instance->smallImageUrl = $imageUrl === null ? null : str_replace('.jpg', 't.jpg', $imageUrl);
        $instance->largeImageUrl = $imageUrl === null ? null : str_replace('.jpg', 'l.jpg', $imageUrl);

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
