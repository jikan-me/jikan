<?php

namespace Jikan\Model\Top;

use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
use Jikan\Parser\Top\TopListItemParser;

/**
 * Class TopAnimeListItem
 *
 * @package Jikan\Model
 */
class TopAnimeListItem
{
    /**
     * @var int
     */
    private $malId;

    /**
     * @var int
     */
    private $rank;

    /**
     * @var string
     */
    private $title;

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
    private $type;

    /**
     * @var int
     */
    private $episodes;

    /**
     * @var string|null
     */
    private $startDate;

    /**
     * @var string|null
     */
    private $endDate;

    /**
     * @var int
     */
    private $members;

    /**
     * @var float
     */
    private $score;

    /**
     * Create an instance from an AnimeParser parser
     *
     * @param TopListItemParser $parser
     *
     * @return self
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(TopListItemParser $parser): self
    {
        $instance = new self();
        $instance->rank = $parser->getRank();
        $instance->malId = $parser->getMalUrl()->getMalId();
        $instance->title = $parser->getMalUrl()->getTitle();
        $instance->url = $parser->getMalUrl()->getUrl();
        $instance->type = $parser->getType();
        $instance->episodes = $parser->getEpisodes();
        $instance->startDate = $parser->getStartDate();
        $instance->endDate = $parser->getEndDate();
        $instance->members = $parser->getMembers();
        $instance->score = $parser->getScore();
        $instance->images = CommonImageResource::factory($parser->getImage());

        return $instance;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getTitle();
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
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
    public function getTitle(): string
    {
        return $this->title;
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
    public function getType(): string
    {
        return $this->type;
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
    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    /**
     * @return string|null
     */
    public function getEndDate(): ?string
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
     * @return float
     */
    public function getScore(): float
    {
        return $this->score;
    }

    /**
     * @return CommonImageResource
     */
    public function getImages(): CommonImageResource
    {
        return $this->images;
    }
}
