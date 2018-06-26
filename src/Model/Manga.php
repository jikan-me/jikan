<?php

namespace Jikan\Model;


class Manga extends Model
{

	private $mal_id;
	
	private $url;

	private $title;

	private $title_english;

	private $title_synonyms;

	private $title_japanese;

	private $status;

	private $image_url;

	private $type;

	private $volumes;

	private $chapters;

	private $publishing = false;

	private $published_string;

	private $published = [];

	private $rank;

	private $score;

	private $scored_by;

	private $popularity;

	private $members;

	private $favorites;

	private $synopsis;

	private $background;
	
	private $related = [];

	private $genre = [];

	private $author = [];

	private $serialization = [];


	/**
     * Create an instance from an Manga parser
     *
     * @param \Jikan\Parser\Manga $parser
     *
     * @return Manga
     */
    public static function fromParser(\Jikan\Parser\Manga $parser): Manga
    {
        $instance = new self();


        return $instance;
	}
	
    /**
     * @return int
     */
    public function getMalId(): int
    {
        return $this->mal_id;
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getTitleEnglish(): string
    {
        return $this->title_english;
    }

    /**
     * @return string
     */
    public function getTitleJapanese(): string
    {
        return $this->title_japanese;
    }

    /**
     * @return string
     */
    public function getTitleSynonyms(): string
    {
        return $this->title_synonyms;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->image_url;
	}
	
    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }


    /**
     * @return int
     */
    public function getVolumes(): int
    {
        return $this->volumes;
	}

    /**
     * @return int
     */
    public function getChapters(): int
    {
        return $this->chapters;
	}
	
    /**
     * @return bool
     */
    public function isPublishing(): bool
    {
        return $this->publishing;
    }

    /**
     * @return string
     */
    public function getPublishedString(): string
    {
        return $this->published_string;
    }

    /**
     * @return array
     */
    public function getPublished(): array
    {
        return $this->published;
    }	


    /**
     * @return string
     */
    public function getScore(): string
    {
        return $this->score;
    }

    /**
     * @return string
     */
    public function getScoredBy(): string
    {
        return $this->scored_by;
    }

    /**
     * @return string
     */
    public function getRank(): string
    {
        return $this->rank;
    }

    /**
     * @return string
     */
    public function getPopularity(): string
    {
        return $this->popularity;
    }

    /**
     * @return string
     */
    public function getMembers(): string
    {
        return $this->members;
    }

    /**
     * @return string
     */
    public function getFavorites(): string
    {
        return $this->favorites;
    }

    /**
     * @return string
     */
    public function getSynopsis(): string
    {
        return $this->synopsis;
    }

    /**
     * @return string
     */
    public function getBackground(): string
    {
        return $this->background;
	}
	
    /**
     * @return array
     */
    public function getRelated(): array
    {
        return $this->related;
	}
	
    /**
     * @return array
     */
    public function getGenre(): array
    {
        return $this->genre;
	}
	
    /**
     * @return array
     */
    public function getAuthor(): array
    {
        return $this->author;
	}
	
    /**
     * @return array
     */
    public function getSerialization(): array
    {
        return $this->serialization;
    }
}