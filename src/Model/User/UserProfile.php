<?php

namespace Jikan\Model\User;

use Jikan\Parser\User\Profile\UserProfileParser;

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
    private $username;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string|null
     */
    private $imageUrl;

    /**
     * @var string
     */
    private $lastOnline;

    /**
     * @var string
     */
    private $gender;

    /**
     * @var \DateTimeImmutable|null
     */
    private $birthday;

    /**
     * @var string
     */
    private $location;

    /**
     * @var \DateTimeImmutable|null
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
     * @var Favorites
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
        $instance->username = $parser->getUsername();
        $instance->url = $parser->getProfileUrl();
        $instance->imageUrl = $parser->getImageUrl();
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
    public function getUsername(): string
    {
        return $this->username;
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
     * @return \DateTimeImmutable|null
     */
    public function getBirthday(): ?\DateTimeImmutable
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
     * @return \DateTimeImmutable|null
     */
    public function getJoined(): ?\DateTimeImmutable
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
     * @return Favorites
     */
    public function getFavorites(): Favorites
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
        return $this->imageUrl;
    }
}
