<?php

namespace Jikan\Model\Search;

use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
use Jikan\Parser;

/**
 * Class MangaSearchListItem
 *
 * @package Jikan\Model\Search\Search
 */
class MangaSearchListItem
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
    private $publishing;

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
    private $chapters;

    /**
     * @var int
     */
    private $volumes;


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
     * @param Parser\Search\MangaSearchListItemParser $parser
     *
     * @return MangaSearchListItem
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public static function fromParser(Parser\Search\MangaSearchListItemParser $parser): self
    {
        $instance = new self();

        $instance->url = $parser->getUrl();
        $instance->malId = \Jikan\Helper\Parser::idFromUrl($instance->url);
        $instance->images = CommonImageResource::factory($parser->getImageUrl());
        $instance->title = $parser->getTitle();
        $instance->synopsis = $parser->getSynopsis();
        $instance->type = $parser->getType();
        $instance->volumes = $parser->getVolumes();
        $instance->chapters = $parser->getChapters();
        $instance->score = $parser->getScore();
        $instance->startDate = $parser->getStartDate();
        $instance->endDate = $parser->getEndDate();
        $instance->members = $parser->getMembers();
        $instance->publishing =
            null === $instance->endDate
            && null !== $instance->startDate
            &&
            (
                new \DateTimeImmutable(
                    'now',
                    new \DateTimeZone('UTC')
                ) > $instance->startDate
            );

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
    public function getChapters(): int
    {
        return $this->chapters;
    }

    /**
     * @return int
     */
    public function getVolumes(): int
    {
        return $this->volumes;
    }

    /**
     * @return float
     */
    public function getScore(): float
    {
        return $this->score;
    }

    /**
     * @return \DateTimeImmutable|null ?\DateTimeImmutable
     */
    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * @return \DateTimeImmutable|null ?\DateTimeImmutable
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
     * @return bool
     */
    public function isPublishing(): bool
    {
        return $this->publishing;
    }
}
