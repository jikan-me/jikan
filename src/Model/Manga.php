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

	private $volumes_unknown;

	private $chapters;

	private $chapters_unknown;

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

        $instance->title = $parser->getMangaTitle();
        $instance->url = $parser->getMangaURL();
        $instance->mal_id = $parser->getMangaID();

        $instance->image_url = $parser->getMangaImageURL();
        $instance->synopsis = $parser->getMangaSynopsis();
        $instance->title_english = $parser->getMangaTitleEnglish();
        $instance->title_synonyms = $parser->getMangaTitleSynonyms();
        $instance->title_japanese = $parser->getMangaTitleJapanese();
        $instance->type = $parser->getMangaType();
        $instance->chapters = $parser->getMangaChapters();
        $instance->volumes = $parser->getMangaVolumes();
        $instance->chapters_unknown = $instance->chapters === 0;
        $instance->volumes_unknown = $instance->volumes === 0;
        $instance->status = $parser->getMangaStatus();
        $instance->published = $instance->status === 'Publishing';
        $instance->published_string = $parser->getMangaPublishedString();

        if (!empty($instance->published_string) && $instance->published_string != 'Not available') {
            if (strpos($instance->published_string, 'to')) {
                preg_match('~(.*) to (.*)~', $instance->published_string, $matches);
                $instance->aired = [
                    'from' => (strpos($matches[1], '?') !== false) ? null : @date_format(date_create($matches[1]), 'o-m-d'),
                    'to' => (strpos($matches[2], '?') !== false) ? null : @date_format(date_create($matches[2]), 'o-m-d')
                ];
            } else {
                if (
                    preg_match('~^[0-9]{4}$~', $instance->published_string)
                    || preg_match('~^[A-Za-z]{1,}, [0-9]{4}$~', $instance->published_string)
                    ) 
                {
                    $instance->aired = [
                        'from' => null,
                        'to' => null
                    ];
                } else {
                    $instance->aired = [
                        'from' => (strpos($instance->published_string, '?') !== false) ? null : @date_format(date_create($instance->published_string), 'o-m-d'),
                        'to' => (strpos($instance->published_string, '?') !== false) ? null : @date_format(date_create($instance->published_string), 'o-m-d')
                    ];
                }
            }
        } else {
            $instance->aired = [
                'from' => null,
                'to' => null
            ];
        }

        $instance->genre = $parser->getMangaGenre();
        $instance->rating = $parser->getMangaRating();

        $scoreString = $parser->getMangaScore();
        preg_match('~(.*)1 \(scored by (.*) users\)~', $scoreString, $matches);
        if ($matches[1] !== 'N/A') {
            $instance->score = (float) $matches[1];
        }
        $instance->scored_by = str_replace(',', '', $matches[2]);
        $instance->rank = $parser->getMangaRank();
        $instance->popularity = $parser->getMangaPopularity();
        $instance->members = $parser->getMangaMembers();
        $instance->favorites = $parser->getMangaFavorites();
        $instance->related = $parser->getMangaRelated();
        $instance->background = $parser->getMangaBackground();

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
    public function getStatus(): string
    {
        return $this->status;
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