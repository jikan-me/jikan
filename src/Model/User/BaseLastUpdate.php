<?php

namespace Jikan\Model\User;

class BaseLastUpdate
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $imageUrl;

    /**
     *
     * @var int|null
     */
    private $progressedSubEntries;

    /**
     * @var int|null
     */
    private $total;

    /**
     * @var string
     */
    private $status;

    /**
     * @var int|null
     */
    private $score;

    /**
     * @var \DateTimeImmutable
     */
    private $date;

    /**
     * BaseLastUpdate constructor.
     * @param string $url
     * @param string $title
     * @param string $imageUrl
     * @param int|null $progressedSubEntries
     * @param int|null $total
     * @param string $status
     * @param int|null $score
     * @param \DateTimeImmutable $date
     */
    public function __construct(string $url, string $title, string $imageUrl, ?int $progressedSubEntries, ?int $total, string $status, ?int $score, \DateTimeImmutable $date)
    {
        $this->progressedSubEntries = $progressedSubEntries;
        $this->total = $total;
        $this->status = $status;
        $this->score = $score;
        $this->date = $date;
        $this->url = $url;
        $this->title = $title;
        $this->imageUrl = $imageUrl;
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
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return int|null
     */
    public function getProgressedSubEntries(): ?int
    {
        return $this->progressedSubEntries;
    }

    /**
     * @return int|null
     */
    public function getTotal(): ?int
    {
        return $this->total;
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
    public function getScore(): ?int
    {
        return $this->score;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }
}
