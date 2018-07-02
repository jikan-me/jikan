<?php

namespace Jikan\Model;

use Jikan\Parser;

/**
 * Class MangaCardParser
 *
 * @package Jikan\Model
 */
class MangaCard
{
    /**
     * @var MalUrl
     */
    protected $url;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $imageUrl;

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
     * @var MalUrl[]
     */
    protected $author;

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
     * @return string
     */
    public function __toString()
    {
        return (string)$this->url;
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
        $instance->url = $parser->getMangaUrl();
        $instance->name = $parser->getTitle();
        $instance->imageUrl = $parser->getMangaImage();
        $instance->synopsis = $parser->getDescription();
        $instance->type = $parser->getType();
        $instance->publishingStart = $parser->getPublishDates();
        $instance->volumes = $parser->getVolumes();
        $instance->members = $parser->getMembers();
        $instance->genres = $parser->getGenres();
        $instance->type = $parser->getType();
        $instance->author = $parser->getAuthor();
        $instance->score = $parser->getMangaScore();
        $instance->serialization = $parser->getSerialization();
        
        $instance->url = $instance->getUrl();
    }

    /**
     * @return MalUrl
     */
    public function getUrl(): MalUrl
    {
        return new MalUrl($this->name, $this->url);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
    public function getPublishingStart(): string
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
    public function getAuthor(): array
    {
        return $this->author;
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
