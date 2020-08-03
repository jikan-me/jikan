<?php

namespace Jikan\Model\Reviews;

use Jikan\Model\Manga\MangaReviewScores;

/**
 * Class UserAnimeReview
 *
 * @package Jikan\Model
 */
abstract class MangaReview extends Review
{

    /**
     * @var string
     */
    protected $chaptersRead;

    /**
     * @var MangaReviewScores
     */
    protected $scores;

    /**
     * @return string
     */
    public function getChaptersRead(): string
    {
        return $this->chaptersRead;
    }

    /**
     * @return MangaReviewScores
     */
    public function getScores(): MangaReviewScores
    {
        return $this->scores;
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getVotes(): int
    {
        return $this->votes;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getReview(): string
    {
        return $this->review;
    }
}
