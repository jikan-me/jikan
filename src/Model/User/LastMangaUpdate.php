<?php

namespace Jikan\Model\User;

use Jikan\Model\Common\MangaMeta;

class LastMangaUpdate
{
    /**
     * @var MangaMeta
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
    private $chaptersRead;

    /**
     * @var int|null
     */
    private $chaptersTotal;

    /**
     * @var \DateTimeImmutable
     */
    private $date;

    /**
     * LastMangaUpdate constructor.
     * @param BaseLastUpdate $baseLastUpdate
     */
    public function __construct(BaseLastUpdate $baseLastUpdate)
    {
        $this->entry = new MangaMeta($baseLastUpdate->getTitle(), $baseLastUpdate->getUrl(), $baseLastUpdate->getImageUrl());
        $this->score = $baseLastUpdate->getScore();
        $this->status = $baseLastUpdate->getStatus();
        $this->chaptersRead = $baseLastUpdate->getProgressedSubEntries();
        $this->chaptersTotal = $baseLastUpdate->getTotal();
        $this->date = $baseLastUpdate->getDate();
    }

    /**
     * @return MangaMeta
     */
    public function getEntry(): MangaMeta
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
    public function getChaptersRead(): ?int
    {
        return $this->chaptersRead;
    }

    /**
     * @return int|null
     */
    public function getChaptersTotal(): ?int
    {
        return $this->chaptersTotal;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }
}
