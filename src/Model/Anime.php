<?php

namespace Jikan\Model;


/**
 * Class Anime
 *
 * @package Jikan\Model
 */
class Anime
{

    /**
     * @var int
     */
    private $mal_id;

    /**
     * @var string
     */
    private $link_canonical;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $title_english;

    /**
     * @var string
     */
    private $title_japanese;

    /**
     * @var string
     */
    private $title_synonyms;

    /**
     * @var string
     */
    private $image_url;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $episodes;

    /**
     * @var string
     */
    private $status;

    /**
     * @var bool
     */
    private $airing = false;

    /**
     * @var string
     */
    private $aired_string;

    /**
     * @var array
     */
    private $aired = [
        'from' => null,
        'to'   => null,
    ];

    /**
     * @var string
     */
    private $duration;

    /**
     * @var string
     */
    private $rating;

    /**
     * @var string
     */
    private $score;

    /**
     * @var string
     */
    private $scored_by;

    /**
     * @var string
     */
    private $rank;

    /**
     * @var string
     */
    private $popularity;

    /**
     * @var string
     */
    private $members;

    /**
     * @var string
     */
    private $favorites;

    /**
     * @var string
     */
    private $synopsis;

    /**
     * @var string
     */
    private $background;

    /**
     * @var string
     */
    private $premiered;

    /**
     * @var string
     */
    private $broadcast;

    /**
     * @var array
     */
    private $related = [];

    /**
     * @var array
     */
    private $producer = [];

    /**
     * @var array
     */
    private $licensor = [];

    /**
     * @var array
     */
    private $studio = [];

    /**
     * @var array
     */
    private $genre = [];

    /**
     * @var array
     */
    private $opening_theme = [];

    /**
     * @var array
     */
    private $ending_theme = [];

    /**
     * Create an instance from an Anime parser
     *
     * @param \Jikan\Parser\Anime $parser
     *
     * @return Anime
     */
    public static function fromParser(\Jikan\Parser\Anime $parser)
    {
        $instance = new self();
        $instance->title = $parser->getAnimeTitle();
        $instance->link_canonical = $parser->getAnimeURL();
        $instance->image_url = $parser->getAnimeImageURL();
        $instance->synopsis = $parser->getAnimeSynopsis();
        $instance->title_english = $parser->getAnimeTitleEnglish();
        $instance->title_synonyms = $parser->getAnimeTitleSynonyms();
        $instance->title_japanese = $parser->getAnimeTitleJapanese();
        $instance->typ = $parser->getAnimeType();

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
    public function getLinkCanonical(): string
    {
        return $this->link_canonical;
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
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getEpisodes(): string
    {
        return $this->episodes;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isAiring(): bool
    {
        return $this->airing;
    }

    /**
     * @return string
     */
    public function getAiredString(): string
    {
        return $this->aired_string;
    }

    /**
     * @return array
     */
    public function getAired(): array
    {
        return $this->aired;
    }

    /**
     * @return string
     */
    public function getDuration(): string
    {
        return $this->duration;
    }

    /**
     * @return string
     */
    public function getRating(): string
    {
        return $this->rating;
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
     * @return string
     */
    public function getPremiered(): string
    {
        return $this->premiered;
    }

    /**
     * @return string
     */
    public function getBroadcast(): string
    {
        return $this->broadcast;
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
    public function getProducer(): array
    {
        return $this->producer;
    }

    /**
     * @return array
     */
    public function getLicensor(): array
    {
        return $this->licensor;
    }

    /**
     * @return array
     */
    public function getStudio(): array
    {
        return $this->studio;
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
    public function getOpeningTheme(): array
    {
        return $this->opening_theme;
    }

    /**
     * @return array
     */
    public function getEndingTheme(): array
    {
        return $this->ending_theme;
    }
}
