<?php

namespace Jikan\Model\User;

use Jikan\Model\Common\AnimeMeta;

class LastAnimeUpdate
{
    /**
     * @var AnimeMeta
     */
    private $entry;

    /**
     * @var int|null
     */
    private $score;

    /**
     * @var string
     */
    private $status;

    /**
     * @var int|null
     */
    private $episodesSeen;

    /**
     * @var int|null
     */
    private $episodesTotal;

    /**
     * @var \DateTimeImmutable
     */
    private $date;

    /**
     * LastAnimeUpdate constructor.
     *
     * @param BaseLastUpdate $baseLastUpdate
     */
    public function __construct(BaseLastUpdate $baseLastUpdate)
    {
        $this->entry = new AnimeMeta($baseLastUpdate->getTitle(), $baseLastUpdate->getUrl(), $baseLastUpdate->getImageUrl());
        $this->score = $baseLastUpdate->getScore();
        $this->status = $baseLastUpdate->getStatus();
        $this->episodesSeen = $baseLastUpdate->getProgressedSubEntries();
        $this->episodesTotal = $baseLastUpdate->getTotal();
        $this->date = $baseLastUpdate->getDate();
    }

    /**
     * @return AnimeMeta
     */
    public function getEntry(): AnimeMeta
    {
        return $this->entry;
    }

    /**
     * @return int|null
     */
    public function getScore(): ?int
    {
        return $this->score;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return int|null
     */
    public function getEpisodesSeen(): ?int
    {
        return $this->episodesSeen;
    }

    /**
     * @return int|null
     */
    public function getEpisodesTotal(): ?int
    {
        return $this->episodesTotal;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }
}
