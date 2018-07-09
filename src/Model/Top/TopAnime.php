<?php

namespace Jikan\Model\Top;

use Jikan\Model\MalUrl;
use Jikan\Parser\Top\TopListItemParser;

/**
 * Class TopAnime
 *
 * @package Jikan\Model
 */
class TopAnime
{
    /**
     * @var int
     */
    private $rank;

    /**
     * @var MalUrl
     */
    private $malUrl;

    /**
     * @var string
     */
    private $image;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $episodes;

    /**
     * @var string
     */
    private $startDate;

    /**
     * @var string
     */
    private $endDate;

    /**
     * @var int
     */
    private $members;

    /**
     * @var float
     */
    private $rating;

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
        $instance->malUrl = $parser->getMalUrl();
        $instance->type = $parser->getType();
        $instance->episodes = $parser->getEpisodes();
        $instance->startDate = $parser->getStartDate();
        $instance->endDate = $parser->getEndDate();
        $instance->members = $parser->getMembers();
        $instance->rating = $parser->getRating();
        $instance->image = $parser->getImage();

        return $instance;
    }

    public function __toString(): string
    {
        return $this->malUrl->getName();
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @return MalUrl
     */
    public function getMalUrl(): MalUrl
    {
        return $this->malUrl;
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
     * @return string
     */
    public function getStartDate(): string
    {
        return $this->startDate;
    }

    /**
     * @return string
     */
    public function getEndDate(): string
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
    public function getRating(): float
    {
        return $this->rating;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }
}
