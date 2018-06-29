<?php

namespace Jikan\Model;

use Jikan\Parser;

/**
 * Class AnimeCardParser
 *
 * @package Jikan\Model
 */
class AnimeCard
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $synopsis;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
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
     * @var array|MalUrl[]
     */
    protected $genres;

    /**
     * @var string
     */
    protected $source;

    /**
     * @var MalUrl[]
     */
    protected $producer;

    /**
     * @var string|null
     */
    protected $imageUrl;

    /**
     * @var string
     */
    protected $malId;

    /**
     * @var string
     */
    protected $url;

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
     */
    protected static function setProperties(Parser\Common\AnimeCardParser $parser, $instance): void
    {
        $instance->title = $parser->getTitle();
        $instance->synopsis = $parser->getDescription();
        $instance->type = $parser->getType();
        $instance->airingStart = $parser->getAirDates();
        $instance->episodes = $parser->getEpisodes();
        $instance->members = $parser->getMembers();
        $instance->genres = $parser->getGenres();
        $instance->source = $parser->getSource();
        $instance->producer = $parser->getProducer();
        $instance->imageUrl = $parser->getAnimeImage();
        $instance->malId = $parser->getAnimeId();
        $instance->url = $parser->getAnimeUrl();
        $instance->score = $parser->getAnimeScore();
        $instance->licensors = $parser->getLicensors();
        $instance->r18 = $parser->isR18();
        $instance->kids = $parser->isKids();
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
    public function getSynopsis(): string
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
     * @return string
     */
    public function getAiringStart(): string
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
     * @return MalUrl[]
     */
    public function getGenres(): array
    {
        return $this->genres;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return MalUrl[]
     */
    public function getProducer(): array
    {
        return $this->producer;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @return string
     */
    public function getMalId(): string
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
