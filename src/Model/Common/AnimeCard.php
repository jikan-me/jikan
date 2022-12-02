<?php

namespace Jikan\Model\Common;

use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
use Jikan\Parser;

/**
 * Class AnimeCardParser
 *
 * @package Jikan\Model
 */
class AnimeCard
{
    /**
     * @var int
     */
    protected $malId;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var CommonImageResource
     */
    protected $images;

    /**
     * @var string|null
     */
    protected $synopsis;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var \DateTimeImmutable|null
     */
    protected $airingStart;

    /**
     * @var int|null
     */
    protected $episodes;

    /**
     * @var int
     */
    protected $members;

    /**
     * @var \Jikan\Model\Common\MalUrl[]
     */
    protected $genres;

    /**
     * @var \Jikan\Model\Common\MalUrl[]
     */
    protected $explicitGenres;

    /**
     * @var \Jikan\Model\Common\MalUrl[]
     */
    protected $themes;

    /**
     * @var \Jikan\Model\Common\MalUrl[]
     */
    protected $demographics;

    /**
     * @var string|null
     */
    protected $source;

    /**
     * @var \Jikan\Model\Common\MalUrl[]
     */
    protected $producers;

    /**
     * @var float|null
     */
    protected $score;

    /**
     * @var string[]|null
     */
    protected $licensors;

    /**
     * @var bool
     */
    protected $r18;

    /**
     * @var bool
     */
    protected $kids;

    /**
     * @param Parser\Common\AnimeCardParser $parser
     *
     * @return AnimeCard
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function parseAnimeCard(Parser\Common\AnimeCardParser $parser): AnimeCard
    {
        $instance = new self();
        self::setProperties($parser, $instance);

        return $instance;
    }

    /**
     * @param Parser\Common\AnimeCardParser $parser
     * @param AnimeCard                     $instance
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    protected static function setProperties(Parser\Common\AnimeCardParser $parser, $instance): void
    {
        $instance->malId = $parser->getMalId();
        $instance->url = $parser->getAnimeUrl();
        $instance->title = $parser->getTitle();
        $instance->images = CommonImageResource::factory($parser->getAnimeImage());
        $instance->synopsis = $parser->getDescription();
        $instance->type = $parser->getType();
        $instance->airingStart = $parser->getAirDates();
        $instance->episodes = $parser->getEpisodes();
        $instance->members = $parser->getMembers();
        $instance->genres = $parser->getGenres();
        $instance->explicitGenres = $parser->getExplicitGenres();
        $instance->themes = $parser->getThemes();
        $instance->demographics = $parser->getDemographics();
        $instance->source = $parser->getSource();
        $instance->producers = $parser->getProducer();
        $instance->score = $parser->getAnimeScore();
        $instance->licensors = $parser->getLicensors();
        $instance->r18 = $parser->isR18();
        $instance->kids = $parser->isKids();
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
    public function getThemes(): array
    {
        return $this->themes;
    }

    /**
     * @return MalUrl[]
     */
    public function getDemographics(): array
    {
        return $this->demographics;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->url;
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
     * @return CommonImageResource
     */
    public function getImages(): CommonImageResource
    {
        return $this->images;
    }

    /**
     * @return string|null
     */
    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getAiringStart(): ?\DateTimeImmutable
    {
        return $this->airingStart;
    }

    /**
     * @return int|null
     */
    public function getEpisodes(): ?int
    {
        return $this->episodes;
    }

    /**
     * @return int
     */
    public function getMembers(): int
    {
        return $this->members;
    }

    /**
     * @return \Jikan\Model\Common\MalUrl[]
     */
    public function getGenres(): array
    {
        return $this->genres;
    }

    /**
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * @return \Jikan\Model\Common\MalUrl[]
     */
    public function getProducers(): array
    {
        return $this->producers;
    }

    /**
     * @return float|null
     */
    public function getScore(): ?float
    {
        return $this->score;
    }

    /**
     * @return string[]|null
     */
    public function getLicensors(): ?array
    {
        return $this->licensors;
    }

    /**
     * @return bool
     */
    public function isR18(): bool
    {
        return $this->r18;
    }

    /**
     * @return bool
     */
    public function isKids(): bool
    {
        return $this->kids;
    }
}
