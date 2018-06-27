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

	public $alternateName;

	public $birthday;

	public $memberFavorites;

	public $more;

	public $voiceActingRole = [];

	public $animeStaffPosition = [];

	public $publishedManga = [];


    /**
     * @param Parser\Person $parser
     *
     * @return Person
     */
    public static function fromParser(\Jikan\Parser\Person $parser): self
    {
        $instance = new self();

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
    public function getAlternateName(): string
    {
        return $this->alternateName;
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
    public function getMore(): string
    {
        return $this->more;
	}

    /**
     * @return array
     */
    public function getVoiceActingRole(): array
    {
        return $this->voiceActingRole;
	}

    /**
     * @return array
     */
    public function getAnimeStaffPosition(): array
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