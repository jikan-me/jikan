<?php

namespace Jikan\Model;

use Jikan\Parser\UserProfile\UserProfileParser;

/**
 * Class UserProfile
 *
 * @package Jikan\Model
 */
class UserProfile
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string|null
     */
    private $image_url;

    /**
     * @var string
     */
    private $lastOnline;

    /**
     * @var string
     */
    private $gender;

    /**
     * @var string
     */
    private $birthday;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $joined;

    /**
     * @var AnimeStats
     */
    private $animeStats;

    /**
     * @var MangaStats
     */
    private $mangaStats;

    /**
     * @var array
     */
    private $favorites;

    /**
     * @var string
     */
    private $about;

    /**
     * @param UserProfileParser $parser
     *
     * @return UserProfile
     * @throws \InvalidArgumentException
     */
    public static function fromParser(UserProfileParser $parser): self
    {
        $instance = new self();
        $instance->name = $parser->getUsername();
        $instance->url = $parser->getProfileUrl();
        $instance->image_url = $parser->getImageUrl();
        $instance->joined = $parser->getJoinDate();
        $instance->lastOnline = $parser->getLastOnline();
        $instance->gender = $parser->getGender();
        $instance->birthday = $parser->getBirthday();
        $instance->location = $parser->getLocation();
        $instance->animeStats = $parser->getAnimeStats();
        $instance->mangaStats = $parser->getMangaStats();
        $instance->about = $parser->getAbout();
        $instance->favorites = $parser->getFavorites();

        return $instance;
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
    public function getLastOnline(): string
    {
        return $this->lastOnline;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @return string
     */
    public function getBirthday(): string
    {
        return $this->birthday;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getJoined(): string
    {
        return $this->joined;
    }

    /**
     * @return AnimeStats
     */
    public function getAnimeStats(): AnimeStats
    {
        return $this->animeStats;
    }

    /**
     * @return MangaStats
     */
    public function getMangaStats(): MangaStats
    {
        return $this->mangaStats;
    }

    /**
     * @return array
     */
    public function getFavorites(): array
    {
        return $this->favorites;
    }

    /**
     * @return string
     */
    public function getAbout(): string
    {
        return $this->about;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return null|string
     */
    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }
}
