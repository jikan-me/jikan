<?php

namespace Jikan\Model;

use Jikan\Parser;

/**
 * Class SeasonalAnime
 *
 * @package Jikan\Model
 */
class SeasonalAnime extends Model
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $synopsis;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $airingStart;

    /**
     * @var int|null
     */
    private $episodes;

    /**
     * @var int
     */
    private $members;

    /**
     * @var array
     */
    private $genres;

    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $producer;

    /**
     * @var string
     */
    private $imageUrl;

    /**
     * @var string
     */
    private $malId;

    /**
     * @var string
     */
    private $url;

    /**
     * @var float|null
     */
    private $score;

    /**
     * @var string
     */
    private $licensors;

    /**
     * @var bool
     */
    private $r18;

    /**
     * @var bool
     */
    private $kids;

    /**
     * @param Parser\SeasonalAnime $parser
     *
     * @return SeasonalAnime
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\SeasonalAnime $parser): self
    {
        $instance = new self();
        $instance->title = $parser->getTitle();
        $instance->synopsis = $parser->getDescription();
        $instance->type = $parser->getType();
        $instance->airingStart = $parser->getAirDates();
        $instance->episodes = $parser->getEpisodes();
        $instance->members = $parser->getMembers();
        $instance->genres = $parser->getGenres();
        $instance->source = $parser->getSource();
        $instance->producer = $parser->getStudio();
        $instance->imageUrl = $parser->getAnimeImage();
        $instance->malId = $parser->getAnimeId();
        $instance->url = $parser->getAnimeUrl();
        $instance->score = $parser->getAnimeScore();
        $instance->licensors = $parser->getLicensors();
        $instance->r18 = $parser->isR18();
        $instance->kids = $parser->isKids();

        return $instance;
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
     * @return array
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
     * @return string
     */
    public function getProducer(): string
    {
        return $this->producer;
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
     * @return string
     */
    public function getLicensors(): string
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
