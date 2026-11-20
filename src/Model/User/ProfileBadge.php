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
     * @var string
     */
    private string $imageUrl;

    /**
     * @param string $name
     * @param string $imageUrl
     */
    public function __construct(string $name, string $imageUrl)
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
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
}
