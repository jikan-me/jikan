<?php

namespace Jikan\Model;

/**
 * Class AnimeParser
 *
 * @package Jikan\Model
 */
class Anime
{

    /**
     * @var int
     */
    private $malId;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $titleEnglish;

    /**
     * @var string
     */
    private $titleJapanese;

    /**
     * @var string
     */
    private $titleSynonyms;

    /**
     * @var string
     */
    private $imageUrl;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $source;

    /**
     * @var int
     */
    private $episodes;

    /**
     * @var bool
     */
    private $episodesUnknown;

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
    private $airedString;

    /**
     * @var DateRange
     */
    private $aired;

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
    private $scoredBy;

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
    private $licensors = [];

    /**
     * @var array
     */
    private $studios = [];

    /**
     * @var array
     */
    private $genres = [];

    /**
     * @var array
     */
    private $openingTheme = [];

    /**
     * @var array
     */
    private $endingTheme = [];

    /**
     * Create an instance from an AnimeParser parser
     *
     * @param \Jikan\Parser\Anime\AnimeParser $parser
     *
     * @return Anime
     */
    public static function fromParser(\Jikan\Parser\Anime\AnimeParser $parser): Anime
    {
        $instance = new self();
        $instance->title = $parser->getTitle();
        $instance->url = $parser->getURL();
        $instance->malId = $parser->getId();
        $instance->imageUrl = $parser->getImageURL();
        $instance->synopsis = $parser->getSynopsis();
        $instance->titleEnglish = $parser->getTitleEnglish();
        $instance->titleSynonyms = $parser->getTitleSynonyms();
        $instance->titleJapanese = $parser->getTitleJapanese();
        $instance->type = $parser->getType();
        $instance->episodes = $parser->getEpisodes();
        $instance->episodesUnknown = $instance->episodes === 0;
        $instance->status = $parser->getStatus();
        $instance->airing = $instance->status === 'Currently Airing';
        $instance->airedString = $parser->getAnimeAiredString();
        $instance->aired = $parser->getAired();
        $instance->premiered = $parser->getPremiered();
        $instance->broadcast = $parser->getBroadcast();
        $instance->producer = $parser->getProducers();
        $instance->licensors = $parser->getLicensors();
        $instance->studios = $parser->getStudios();
        $instance->source = $parser->getSource();
        $instance->genres = $parser->getGenres();
        $instance->duration = $parser->getDuration();
        $instance->rating = $parser->getRating();
        $instance->score = $parser->getScore();
        $instance->scoredBy = $parser->getScoredBy();
        $instance->rank = $parser->getRank();
        $instance->popularity = $parser->getPopularity();
        $instance->members = $parser->getMembers();
        $instance->favorites = $parser->getFavorites();
        $instance->related = $parser->getRelated();
        $instance->background = $parser->getBackground();
        $instance->openingTheme = $parser->getOpeningThemes();
        $instance->endingTheme = $parser->getEndingThemes();

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
    public function getEpisodesUnknown(): string
    {
        return $this->episodesUnknown;
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
        return $this->airedString;
    }

    /**
     * @return DateRange
     */
    public function getAired(): DateRange
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
    public function getProducers(): array
    {
        return $this->producer;
    }

    /**
     * @return array
     */
    public function getLicensors(): array
    {
        return $this->licensors;
    }

    /**
     * @return array
     */
    public function getStudios(): array
    {
        return $this->studios;
    }

    /**
     * @return array
     */
    public function getGenres(): array
    {
        return $this->genres;
    }

    /**
     * @return array
     */
    public function getOpeningTheme(): array
    {
        return $this->openingTheme;
    }

    /**
     * @return array
     */
    public function getEndingTheme(): array
    {
        return $this->endingTheme;
    }
}
