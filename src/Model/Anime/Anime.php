<?php

namespace Jikan\Model\Anime;

use Jikan\Model\Common\DateRange;
use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Anime\AnimeParser;

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
    private $imageUrl;

    /**
     * @var string|null
     */
    private $trailerUrl;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string|null
     */
    private $titleEnglish;

    /**
     * @var string|null
     */
    private $titleJapanese;

    /**
     * @var string[]
     */
    private $titleSynonyms;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var string|null
     */
    private $source;

    /**
     * @var int|null
     */
    private $episodes;

    /**
     * @var string|null
     */
    private $status;

    /**
     * @var bool
     */
    private $airing = false;

    /**
     * @var DateRange
     */
    private $aired;

    /**
     * @var string|null
     */
    private $duration;

    /**
     * @var string|null
     */
    private $rating;

    /**
     * @var float|null
     */
    private $score;

    /**
     * @var int|null
     */
    private $scoredBy;

    /**
     * @var int|null
     */
    private $rank;

    /**
     * @var int|null
     */
    private $popularity;

    /**
     * @var int|null
     */
    private $members;

    /**
     * @var int|null
     */
    private $favorites;

    /**
     * @var string|null
     */
    private $synopsis;

    /**
     * @var string|null
     */
    private $background;

    /**
     * @var string|null
     */
    private $premiered;

    /**
     * @var string|null
     */
    private $broadcast;

    /**
     * @var MalUrl[]
     */
    private $related = [];

    /**
     * @var \Jikan\Model\Common\MalUrl[]
     */
    private $producers = [];

    /**
     * @var MalUrl[]
     */
    private $licensors = [];

    /**
     * @var MalUrl[]
     */
    private $studios = [];

    /**
     * @var MalUrl[]
     */
    private $genres = [];

    /**
     * @var string[]
     */
    private $openingThemes = [];

    /**
     * @var string[]
     */
    private $endingThemes = [];

    /**
     * Create an instance from an AnimeParser parser
     *
     * @param AnimeParser $parser
     *
     * @return Anime
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(AnimeParser $parser): Anime
    {
        $instance = new self();
        $instance->trailerUrl = $parser->getPreview();
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
        $instance->status = $parser->getStatus();
        $instance->airing = $instance->status === 'Currently Airing';
        $instance->aired = $parser->getAired();
        $instance->premiered = $parser->getPremiered();
        $instance->broadcast = $parser->getBroadcast();
        $instance->producers = $parser->getProducers();
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
        $instance->openingThemes = $parser->getOpeningThemes();
        $instance->endingThemes = $parser->getEndingThemes();
        $instance->background = $parser->getBackground();

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
     * @return string|null
     */
    public function getTitleEnglish(): ?string
    {
        return $this->titleEnglish;
    }

    /**
     * @return string|null
     */
    public function getTitleJapanese(): ?string
    {
        return $this->titleJapanese;
    }

    /**
     * @return string[]
     */
    public function getTitleSynonyms(): array
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
     * @return string|null
     */
    public function getTrailerUrl(): ?string
    {
        return $this->trailerUrl;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * @return int|null
     */
    public function getEpisodes(): ?int
    {
        return $this->episodes;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
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
     * @return \Jikan\Model\Common\DateRange
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
     * @return string|null
     */
    public function getRating(): ?string
    {
        return $this->rating;
    }

    /**
     * @return float|null
     */
    public function getScore(): ?float
    {
        return $this->score;
    }

    /**
     * @return int|null
     */
    public function getScoredBy(): ?int
    {
        return $this->scoredBy;
    }

    /**
     * @return int|null
     */
    public function getRank(): ?int
    {
        return $this->rank;
    }

    /**
     * @return int|null
     */
    public function getPopularity(): ?int
    {
        return $this->popularity;
    }

    /**
     * @return int|null
     */
    public function getMembers(): ?int
    {
        return $this->members;
    }

    /**
     * @return int|null
     */
    public function getFavorites(): ?int
    {
        return $this->favorites;
    }

    /**
     * @return string|null
     */
    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    /**
     * @return string|null
     */
    public function getBackground(): ?string
    {
        return $this->background;
    }

    /**
     * @return string|null
     */
    public function getPremiered(): ?string
    {
        return $this->premiered;
    }

    /**
     * @return string|null
     */
    public function getBroadcast(): ?string
    {
        return $this->broadcast;
    }

    /**
     * @return MalUrl[]
     */
    public function getRelated(): array
    {
        return $this->related;
    }

    /**
     * @return \Jikan\Model\Common\MalUrl[]
     */
    public function getProducers(): array
    {
        return $this->producers;
    }

    /**
     * @return MalUrl[]
     */
    public function getLicensors(): array
    {
        return $this->licensors;
    }

    /**
     * @return \Jikan\Model\Common\MalUrl[]
     */
    public function getStudios(): array
    {
        return $this->studios;
    }

    /**
     * @return MalUrl[]
     */
    public function getGenres(): array
    {
        return $this->genres;
    }

    /**
     * @return string[]
     */
    public function getOpeningThemes(): array
    {
        return $this->openingThemes;
    }

    /**
     * @return string[]
     */
    public function getEndingThemes(): array
    {
        return $this->endingThemes;
    }
}
