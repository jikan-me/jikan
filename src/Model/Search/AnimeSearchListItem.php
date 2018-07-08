<?php

namespace Jikan\Model\Search;

use Jikan\Model\MalUrl;
use Jikan\Parser;

/**
 * Class AnimeSearchListItem
 *
 * @package Jikan\Model\Search
 */
class AnimeSearchListItem
{

    /**
     * @var MalUrl
     */
    private $url;

    /**
     * @var string
     */
    private $imageUrl;

    /**
     * @var string
     */
    private $title;

    /**
     * @var bool
     */
    private $airing;

    /**
     * @var string
     */
    private $synopsis;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $episodes;

    /**
     * @var float
     */
    private $score;

    /**
     * @var ?\DateTimeImmutable
     */
    private $startDate;

    /**
     * @var ?\DateTimeImmutable
     */
    private $endDate;

    /**
     * @var ?string
     */
    private $startDateString;

    /**
     * @var ?string
     */
    private $endDateString;

    /**
     * @var int
     */
    private $members;

    /**
     * @var string
     */
    private $rated;

    /**
     * @param Parser\Search\AnimeSearchListItemParser $parser
     *
     * @return AnimeSearchListItem
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\Search\AnimeSearchListItemParser $parser): self
    {
        $instance = new self();

        $instance->url = $parser->getUrl();
        $instance->imageUrl = $parser->getImageUrl();
        $instance->title = $parser->getTitle();
        $instance->synopsis = $parser->getSynopsis();
        $instance->type = $parser->getType();
        $instance->episodes = $parser->getEpisodes();
        $instance->score = $parser->getScore();
        $instance->startDateString = $parser->getStartDateString();
        $instance->endDateString = $parser->getEndDateString();
        $instance->startDate = $parser->getStartDate();
        $instance->endDate = $parser->getEndDate();
        $instance->members = $parser->getMembers();
        $instance->rated = $parser->getRated();
        $instance->airing =
            is_null($instance->endDate)
            &&
            (
                new \DateTimeImmutable(
                    "now",
                    new \DateTimeZone('UTC')
                ) > $instance->startDate
            );

        return $instance;
    }


    /**
     * @return MalUrl
     */
    public function getUrl(): MalUrl
    {
        return $this->url;
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
     * @return int
     */
    public function getEpisodes(): int
    {
        return $this->episodes;
    }

    /**
     * @return float
     */
    public function getScore(): float
    {
        return $this->score;
    }

    /**
     * @return ?\DateTimeImmutable
     */
    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * @return ?\DateTimeImmutable
     */
    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * @return int
     */
    public function getMembers(): int
    {
        return $this->members;
    }

    /**
     * @return string
     */
    public function getRated(): string
    {
        return $this->rated;
    }

    /**
     * @return ?string
     */
    public function getStartDateString(): ?string
    {
        return $this->startDateString;
    }

    /**
     * @return ?string
     */
    public function getEndDateString(): ?string
    {
        return $this->endDateString;
    }

    /**
     * @return bool
     */
    public function isAiring(): bool
    {
        return $this->airing;
    }
}
