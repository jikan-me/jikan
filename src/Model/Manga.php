<?php

namespace Jikan\Model;


class Manga extends Model
{

	private $malId;
	
	private $url;

	private $title;

	private $titleEnglish;

	private $titleSynonyms;

	private $titleJapanese;

	private $status;

	private $imageUrl;

	private $type;

	private $volumes;

	private $volumesUnknown;

	private $chapters;

	private $chaptersUnknown;

	private $publishing = false;

	private $publishedString;

	private $published = [];

	private $rank;

	private $score;

	private $scoredBy;

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
        $instance->malId = $parser->getMangaID();

        $instance->imageUrl = $parser->getMangaImageURL();
        $instance->synopsis = $parser->getMangaSynopsis();
        $instance->titleEnglish = $parser->getMangaTitleEnglish();
        $instance->titleSynonyms = $parser->getMangaTitleSynonyms();
        $instance->titleJapanese = $parser->getMangaTitleJapanese();
        $instance->type = $parser->getMangaType();
        $instance->chapters = $parser->getMangaChapters();
        $instance->volumes = $parser->getMangaVolumes();
        $instance->chaptersUnknown = $instance->chapters === 0;
        $instance->volumesUnknown = $instance->volumes === 0;
        $instance->status = $parser->getMangaStatus();
        $instance->publishing = $instance->status === 'Publishing';
        $instance->publishedString = $parser->getMangaPublishedString();

        if (!empty($instance->publishedString) && $instance->publishedString != 'Not available') {
            if (strpos($instance->publishedString, 'to')) {
                preg_match('~(.*) to (.*)~', $instance->publishedString, $matches);
                $instance->published = [
                    'from' => (strpos($matches[1], '?') !== false) ? null : @date_format(date_create($matches[1]), 'o-m-d'),
                    'to' => (strpos($matches[2], '?') !== false) ? null : @date_format(date_create($matches[2]), 'o-m-d')
                ];
            } else {
                if (
                    preg_match('~^[0-9]{4}$~', $instance->publishedString)
                    || preg_match('~^[A-Za-z]{1,}, [0-9]{4}$~', $instance->publishedString)
                    ) 
                {
                    $instance->published = [
                        'from' => null,
                        'to' => null
                    ];
                } else {
                    $instance->published = [
                        'from' => (strpos($instance->publishedString, '?') !== false) ? null : @date_format(date_create($instance->publishedString), 'o-m-d'),
                        'to' => (strpos($instance->publishedString, '?') !== false) ? null : @date_format(date_create($instance->publishedString), 'o-m-d')
                    ];
                }
            }
        } else {
            $instance->published = [
                'from' => null,
                'to' => null
            ];
        }

        $instance->genre = $parser->getMangaGenre();
        $instance->score = $parser->getMangaScore();
        $instance->scoredBy = $parser->getMangaScoredBy();
        $instance->rank = $parser->getMangaRank();
        $instance->popularity = $parser->getMangaPopularity();
        $instance->members = $parser->getMangaMembers();
        $instance->favorites = $parser->getMangaFavorites();
        $instance->related = $parser->getMangaRelated();
        $instance->background = $parser->getMangaBackground();
        $instance->author = $parser->getMangaAuthors();
        $instance->serialization = $parser->getMangaSerialization();

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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getTitleEnglish(): string
    {
        return $this->titleEnglish;
    }

    /**
     * @return string
     */
    public function getTitleJapanese(): string
    {
        return $this->titleJapanese;
    }

    /**
     * @return string
     */
    public function getTitleSynonyms(): string
    {
        return $this->titleSynonyms;
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
        return $this->imageUrl;
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
     * @return bool
     */
    public function isVolumesUnknown(): bool
    {
        return $this->volumesUnknown;
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
    public function isChaptersUnknown(): bool
    {
        return $this->chaptersUnknown;
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
        return $this->publishedString;
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
        return $this->scoredBy;
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