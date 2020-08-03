<?php

namespace Jikan\Model\Search;

use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
use Jikan\Parser;

/**
 * Class AnimeSearchListItem
 *
 * @package Jikan\Model\Search\Search
 */
class AnimeSearchListItem
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
     * @var CommonImageResource
     */
    private $images;

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
     * @var int
     */
    private $members;

    /**
     * @var ?string
     */
    private $rated;

    /**
     * @param Parser\Search\AnimeSearchListItemParser $parser
     *
     * @return AnimeSearchListItem
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public static function fromParser(Parser\Search\AnimeSearchListItemParser $parser): self
    {
        $instance = new self();

        $instance->url = $parser->getUrl();
        $instance->malId = \Jikan\Helper\Parser::idFromUrl($instance->url);
        $instance->images = CommonImageResource::factory($parser->getImageUrl());
        $instance->title = $parser->getTitle();
        $instance->synopsis = $parser->getSynopsis();
        $instance->type = $parser->getType();
        $instance->episodes = $parser->getEpisodes();
        $instance->score = $parser->getScore();
        $instance->startDate = $parser->getStartDate();
        $instance->endDate = $parser->getEndDate();
        $instance->members = $parser->getMembers();
        $instance->rated = $parser->getRated();
        $instance->airing = $parser->isAiring();

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
     * @return CommonImageResource
     */
    public function getImages(): CommonImageResource
    {
        return $this->images;
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
     * @return \DateTimeImmutable|null
     */
    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * @return \DateTimeImmutable|null
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
     * @return string|null
     */
    public function getRated(): ?string
    {
        return $this->rated;
    }

    /**
     * @return bool
     */
    public function isAiring(): bool
    {
        return $this->airing;
    }
}
