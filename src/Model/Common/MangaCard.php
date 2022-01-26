<?php

namespace Jikan\Model\Common;

use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
use Jikan\Parser;

/**
 * Class MangaCardParser
 *
 * @package Jikan\Model
 */
class MangaCard
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
     * @var string
     */
    protected $synopsis;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var \DateTimeImmutable|null
     */
    protected $publishingStart;

    /**
     * @var int|null
     */
    protected $volumes;

    /**
     * @var int
     */
    protected $members;

    /**
     * @var MalUrl[]
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
     * @var MalUrl[]
     */
    protected $authors;

    /**
     * @var float|null
     */
    protected $score;

    /**
     * @var string[]|null
     */
    protected $serialization;

    /**
     * @param Parser\Common\MangaCardParser $parser
     *
     * @return MangaCard
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function parseMangaCard(Parser\Common\MangaCardParser $parser): MangaCard
    {
        $instance = new self();
        self::setProperties($parser, $instance);

        return $instance;
    }

    /**
     * @param Parser\Common\MangaCardParser $parser
     * @param MangaCard                     $instance
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    protected static function setProperties(Parser\Common\MangaCardParser $parser, $instance): void
    {
        $instance->malId = $parser->getMalId();
        $instance->url = $parser->getMangaUrl();
        $instance->title = $parser->getTitle();
        $instance->images = CommonImageResource::factory($parser->getMangaImage());
        $instance->synopsis = $parser->getDescription();
        $instance->type = $parser->getType();
        $instance->publishingStart = $parser->getPublishDates();
        $instance->volumes = $parser->getVolumes();
        $instance->members = $parser->getMembers();
        $instance->genres = $parser->getGenres();
        $instance->explicitGenres = $parser->getExplicitGenres();
        $instance->themes = $parser->getThemes();
        $instance->demographics = $parser->getDemographics();
        $instance->authors = $parser->getAuthor();
        $instance->score = $parser->getMangaScore();
        $instance->serialization = $parser->getSerialization();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->url;
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
     * @return string
     */
    public function getSynopsis(): string
    {
        return $this->synopsis;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getPublishingStart(): ?\DateTimeImmutable
    {
        return $this->publishingStart;
    }

    /**
     * @return int|null
     */
    public function getVolumes(): ?int
    {
        return $this->volumes;
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
     * @return MalUrl[]
     */
    public function getAuthors(): array
    {
        return $this->authors;
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
    public function getSerialization(): ?array
    {
        return $this->serialization;
    }
}
