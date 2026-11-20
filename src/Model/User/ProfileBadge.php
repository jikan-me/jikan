<?php

namespace Jikan\Model\User;

/**
 * A profile badge MyAnimeList awards for events and site activity — e.g. "MALoween Mansion
 * (Trapped Soul)" — with its artwork. The profile shows the eight most recent; the full list lives
 * on the user's /badges page.
 *
 * @package Jikan\Model
 */
class ProfileBadge
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string|null
     */
    private ?string $imageUrl;

    /**
     * @param string $name
     * @param string|null $imageUrl
     */
    public function __construct(string $name, ?string $imageUrl)
    {
        $this->name = $name;
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * MyAnimeList placeholder assets normalize to null, like every other image getter.
     *
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }
}
