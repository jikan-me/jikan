<?php

namespace Jikan\Model\Person;

use Jikan\Model\Resource\PersonImageResource\PersonImageResource;
use Jikan\Parser\Person\PersonParser;

/**
 * Class Person
 *
 * @package Jikan\Model
 */
class Person
{
    /**
     * @var int
     */
    public $malId;

    /**
     * @var string
     */
    public $url;

    /**
     * @var PersonImageResource
     */
    public $images;

    /**
     * @var string|null
     */
    public $websiteUrl;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string|null
     */
    public $givenName;

    /**
     * @var string|null
     */
    public $familyName;

    /**
     * @var string[]
     */
    public $alternateNames;

    /**
     * @var \DateTimeImmutable|null
     */
    public $birthday;

    /**
     * @var int
     */
    public $memberFavorites;

    /**
     * @var string|null
     */
    public $about;

    /**
     * @var VoiceActingRole[]
     */
    public $voiceActingRoles = [];

    /**
     * @var AnimeStaffPosition[]
     */
    public $animeStaffPositions = [];

    /**
     * @var PublishedManga[]
     */
    public $publishedManga = [];

    /**
     * @param PersonParser $parser
     *
     * @return Person
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(PersonParser $parser): self
    {
        $instance = new self();
        $instance->malId = $parser->getPersonId();
        $instance->url = $parser->getPersonURL();
        $instance->images = PersonImageResource::factory($parser->getPersonImageUrl());
        $instance->name = $parser->getPersonName();
        $instance->givenName = $parser->getPersonGivenName();
        $instance->familyName = $parser->getPersonFamilyName();
        $instance->alternateNames = $parser->getPersonAlternateNames();
        $instance->websiteUrl = $parser->getPersonWebsite();
        $instance->birthday = $parser->getPersonBirthday();
        $instance->about = $parser->getPersonAbout();
        $instance->memberFavorites = $parser->getPersonFavorites();
        $instance->voiceActingRoles = $parser->getPersonVoiceActingRoles();
        $instance->animeStaffPositions = $parser->getPersonAnimeStaffPositions();
        $instance->publishedManga = $parser->getPersonPublishedManga();

        return $instance;
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        return $this->malId;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return PersonImageResource
     */
    public function getImages(): PersonImageResource
    {
        return $this->images;
    }

    /**
     * @return string|null
     */
    public function getWebsiteUrl(): ?string
    {
        return $this->websiteUrl;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getGivenName(): ?string
    {
        return $this->givenName;
    }

    /**
     * @return string|null
     */
    public function getFamilyName(): ?string
    {
        return $this->familyName;
    }

    /**
     * @return string[]
     */
    public function getAlternateNames(): array
    {
        return $this->alternateNames;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getBirthday(): ?\DateTimeImmutable
    {
        return $this->birthday;
    }

    /**
     * @return int
     */
    public function getMemberFavorites(): int
    {
        return $this->memberFavorites;
    }

    /**
     * @return string|null
     */
    public function getAbout(): ?string
    {
        return $this->about;
    }

    /**
     * @return VoiceActingRole[]
     */
    public function getVoiceActingRoles(): array
    {
        return $this->voiceActingRoles;
    }

    /**
     * @return AnimeStaffPosition[]
     */
    public function getAnimeStaffPositions(): array
    {
        return $this->animeStaffPositions;
    }

    /**
     * @return PublishedManga[]
     */
    public function getPublishedManga(): array
    {
        return $this->publishedManga;
    }
}
