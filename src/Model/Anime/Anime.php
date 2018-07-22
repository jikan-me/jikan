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
     * @var string[]
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
    private $openingTheme = [];

    /**
     * @var string[]
     */
    private $endingTheme = [];

    /**
     * @var string|null
     */
    private $previewVideoUrl;

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
        $instance->previewVideoUrl = $parser->getPreview();
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
        $instance->openingTheme = $parser->getOpeningThemes();
        $instance->endingTheme = $parser->getEndingThemes();
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
     * @return null|string
     */
    public function getPreviewVideoUrl(): ?string
    {
        return $this->previewVideoUrl;
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
     * @return bool
     * @todo shouldn't we return null if its unknow and drop this funtion?
     */
    public function isEpisodesUnknown(): bool
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
    public function getOpeningTheme(): array
    {
        return $this->openingTheme;
    }

    /**
     * @return string[]
     */
    public function getEndingTheme(): array
    {
        return $this->endingTheme;
    }
}
