<?php

namespace Jikan\Model\Anime;

use Jikan\Model\Common\DateRange;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\Common\Url;
use Jikan\Model\Common\YoutubeMeta;
use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
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
    private int $malId;

    /**
     * @var string
     */
    private string $url;

    /**
     * @var CommonImageResource
     */
    private CommonImageResource $images;

    /**
     * @var YoutubeMeta
     */
    private YoutubeMeta $trailer;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var string|null
     */
    private ?string $titleEnglish;

    /**
     * @var string|null
     */
    private ?string $titleJapanese;

    /**
     * @var string[]
     */
    private array $titleSynonyms;

    /**
     * @var \Jikan\Model\Common\Title[]
     */
    private array $titles;

    /**
     * @var bool
     */
    private bool $approved;

    /**
     * @var string|null
     */
    private ?string $type;

    /**
     * @var string|null
     */
    private ?string $source;

    /**
     * @var int|null
     */
    private ?int $episodes;

    /**
     * @var string|null
     */
    private ?string $status;

    /**
     * @var bool
     */
    private bool $airing = false;

    /**
     * @var DateRange
     */
    private DateRange $aired;

    /**
     * @var string|null
     */
    private ?string $duration;

    /**
     * @var string|null
     */
    private ?string $rating;

    /**
     * @var float|null
     */
    private ?float $score;

    /**
     * @var int|null
     */
    private ?int $scoredBy;

    /**
     * @var int|null
     */
    private ?int $rank;

    /**
     * @var int|null
     */
    private ?int $popularity;

    /**
     * @var int|null
     */
    private ?int $members;

    /**
     * @var int|null
     */
    private ?int $favorites;

    /**
     * @var string|null
     */
    private ?string $synopsis;

    /**
     * @var string|null
     */
    private ?string $background;

    /**
     * @var string|null
     */
    private ?string $premiered;

    /**
     * @var string|null
     */
    private ?string $broadcast;

    /**
     * @var MalUrl[]
     */
    private array $related = [];

    /**
     * @var MalUrl[]
     */
    private array $producers = [];

    /**
     * @var MalUrl[]
     */
    private array $licensors = [];

    /**
     * @var MalUrl[]
     */
    private array $studios = [];

    /**
     * @var MalUrl[]
     */
    private array $genres = [];

    /**
     * @var MalUrl[]
     */
    private array $explicitGenres = [];

    /**
     * @var MalUrl[]
     */
    private array $demographics = [];

    /**
     * @var MalUrl[]
     */
    private array $themes = [];

    /**
     * @var string[]
     */
    private array $openingThemes = [];

    /**
     * @var string[]
     */
    private array $endingThemes = [];

    /**
     * @var Url[]
     */
    private array $externalLinks = [];

    /**
     * @var Url[]
     */
    private array $streamingLinks = [];

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
        $instance->trailer = YoutubeMeta::factory($parser->getPreview());
        $instance->title = $parser->getTitle();
        $instance->url = $parser->getURL();
        $instance->malId = $parser->getId();
        $instance->approved = $parser->getApproved();
        $instance->images = CommonImageResource::factory($parser->getImageURL());
        $instance->synopsis = $parser->getSynopsis();
        $instance->titleEnglish = $parser->getTitleEnglish();
        $instance->titleSynonyms = $parser->getTitleSynonyms();
        $instance->titleJapanese = $parser->getTitleJapanese();
        $instance->titles = $parser->getTitles();
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
        $instance->explicitGenres = $parser->getExplicitGenres();
        $instance->demographics = $parser->getDemographics();
        $instance->themes = $parser->getThemes();
        $instance->duration = $parser->getDuration();
        $instance->rating = $parser->getRating();
        $instance->score = $parser->getScore();
        $instance->scoredBy = $parser->getScoredBy();
        $instance->rank = $parser->getRank();
        $instance->popularity = $parser->getPopularity();
        $instance->members = $parser->getMembers();
        $instance->favorites = $parser->getFavorites();
        $instance->externalLinks = $parser->getExternalLinks();
        $instance->streamingLinks = $parser->getStreamingLinks();
        $instance->related = $parser->getRelated();
        $instance->openingThemes = $parser->getOpeningThemes();
        $instance->endingThemes = $parser->getEndingThemes();
        $instance->background = $parser->getBackground();

        return $instance;
    }

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->approved;
    }

    /**
     * @return MalUrl[]
     */
    public function getExplicitGenres(): array
    {
        return $this->explicitGenres;
    }

    /**
     * @return MalUrl[]
     */
    public function getDemographics(): array
    {
        return $this->demographics;
    }

    /**
     * @return MalUrl[]
     */
    public function getThemes(): array
    {
        return $this->themes;
    }

    /**
     * @return Url[]
     */
    public function getExternalLinks(): array
    {
        return $this->externalLinks;
    }

    /**
     * @return Url[]
     */
    public function getStreamingLinks(): array
    {
        return $this->streamingLinks;
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
     * @deprecated Use {@link Anime::getTitles()} instead.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string|null
     * @deprecated Use {@link Anime::getTitles()} instead.
     */
    public function getTitleEnglish(): ?string
    {
        return $this->titleEnglish;
    }

    /**
     * @return string|null
     * @deprecated Use {@link Anime::getTitles()} instead.
     */
    public function getTitleJapanese(): ?string
    {
        return $this->titleJapanese;
    }

    /**
     * @return string[]
     * @deprecated Use {@link Anime::getTitles()} instead.
     */
    public function getTitleSynonyms(): array
    {
        return $this->titleSynonyms;
    }

    /**
     * @return \Jikan\Model\Common\Title[]
     */
    public function getTitles(): array
    {
        return $this->titles;
    }

    /**
     * @return CommonImageResource
     */
    public function getImages(): CommonImageResource
    {
        return $this->images;
    }

    /**
     * @return YoutubeMeta
     */
    public function getTrailer(): YoutubeMeta
    {
        return $this->trailer;
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
