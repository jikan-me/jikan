<?php

namespace Jikan\Model\User;

use Jikan\Model\Common\Url;
use Jikan\Model\Resource\UserImageResource\UserImageResource;
use Jikan\Parser\User\Profile\UserProfileParser;

/**
 * Class Profile
 *
 * @package Jikan\Model
 */
class Profile
{
    /**
     * @var int|null
     */
    private ?int $malId;

    /**
     * @var string
     */
    private string $username;

    /**
     * @var string
     */
    private string $url;

    /**
     * @var UserImageResource
     */
    private UserImageResource $images;

    /**
     * @var \DateTimeImmutable|null
     */
    private ?\DateTimeImmutable $lastOnline;

    /**
     * @var string|null
     */
    private ?string $gender;

    /**
     * @var \DateTimeImmutable|null
     */
    private ?\DateTimeImmutable $birthday;

    /**
     * @var string|null
     */
    private ?string $location;

    /**
     * @var \DateTimeImmutable|null
     */
    private ?\DateTimeImmutable $joined;

    /**
     * @var AnimeStats
     */
    private AnimeStats $animeStats;

    /**
     * @var MangaStats
     */
    private MangaStats $mangaStats;

    /**
     * @var Favorites
     */
    private Favorites $favorites;

    /**
     * @var LastUpdates
     */
    private LastUpdates $lastUpdates;

    /**
     * @var Url[]
     */
    private array $externalLinks = [];

    /**
     * @var string|null
     */
    private ?string $about;

    /**
     * @param UserProfileParser $parser
     *
     * @return Profile
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public static function fromParser(UserProfileParser $parser): self
    {
        $instance = new self();
        $instance->malId = $parser->getUserId();
        $instance->username = $parser->getUsername();
        $instance->url = $parser->getProfileUrl();
        $instance->images = UserImageResource::factory($parser->getImageUrl());
        $instance->joined = $parser->getJoinDate();
        $instance->lastOnline = $parser->getLastOnline();
        $instance->gender = $parser->getGender();
        $instance->birthday = $parser->getBirthday();
        $instance->location = $parser->getLocation();
        $instance->animeStats = $parser->getAnimeStats();
        $instance->mangaStats = $parser->getMangaStats();
        $instance->about = $parser->getAbout();
        $instance->favorites = $parser->getFavorites();
        $instance->lastUpdates = $parser->getUserLastUpdates();
        $instance->externalLinks = $parser->getUserExternalLinks();

        return $instance;
    }

    /**
     * @return int|null
     */
    public function getMalId(): ?int
    {
        return $this->malId;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getLastOnline(): ?\DateTimeImmutable
    {
        return $this->lastOnline;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
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
     * @return string|null
     */
    public function getAbout(): ?string
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
     * @return UserImageResource
     */
    public function getImages(): UserImageResource
    {
        return $this->images;
    }

    /**
     * @return LastUpdates
     */
    public function getLastUpdates(): LastUpdates
    {
        return $this->lastUpdates;
    }

    /**
     * @return Url[]
     */
    public function getExternalLinks(): array
    {
        return $this->externalLinks;
    }
}
