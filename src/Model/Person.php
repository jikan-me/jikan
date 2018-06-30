<?php

namespace Jikan\Model;

class Person extends Model
{

	public $malId;

	public $url;

	public $imageUrl;

	public $websiteUrl;

	public $name;

	public $givenName;

	public $familyName;

	public $alternateNames;

	public $birthday;

	public $memberFavorites;

	public $about;

	public $voiceActingRole = [];

	public $animeStaffPosition = [];

	public $publishedManga = [];


    /**
     * @param Parser\PersonParser $parser
     *
     * @return Person
     */
    public static function fromParser(\Jikan\Parser\Person\PersonParser $parser): self
    {
        $instance = new self();

        $instance->malId = $parser->getPersonId();
        $instance->url = $parser->getPersonUrl();
        $instance->imageUrl = $parser->getPersonImageUrl();
        $instance->name = $parser->getPersonName();
        $instance->givenName = $parser->getPersonGivenName();
        $instance->familyName = $parser->getPersonFamilyName();
        $instance->alternateNames = $parser->getPersonAlternateNames();
        $instance->websiteUrl = $parser->getPersonWebsite();
        $instance->about = $parser->getPersonAbout();
        $instance->memberFavorites = $parser->getPersonFavorites();
        $instance->voiceActingRole = $parser->getPersonVoiceActingRole();

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
     * @return string
     */
    public function getWebsiteUrl(): string
    {
        return $this->websiteUrl;
	}
	

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
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
    public function getGivenName(): string
    {
        return $this->givenName;
	}
	
    /**
     * @return string
     */
    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    /**
     * @return string
     */
    public function getAlternateNames(): string
    {
        return $this->alternateNames;
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
    public function getMemberFavorites(): string
    {
        return $this->memberFavorites;
	}

    /**
     * @return string
     */
    public function getAbout(): string
    {
        return $this->about;
	}

    /**
     * @return array
     */
    public function getVoiceActingRoles(): array
    {
        return $this->voiceActingRole;
	}

    /**
     * @return array
     */
    public function getAnimeStaffPositions(): array
    {
        return $this->animeStaffPosition;
	}

    /**
     * @return array
     */
    public function getPublishedManga(): array
    {
        return $this->publishedManga;
	}
	
}