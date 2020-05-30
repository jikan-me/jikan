<?php

namespace Jikan\Model\Reviews;

/**
 * Class AnimeReview
 *
 * @package Jikan\Model
 */
abstract class AnimeReview
{

    /**
     * @var int
     */
    protected $malId;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $votes;

    /**
     * @var \DateTimeImmutable
     */
    protected $date;

    /**
     * @var AnimeReviewScores
     */
    protected $scores;

    /**
     * @var string
     */
    protected $review;

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
     * @return AnimeReviewScores
     */
    public function getScores(): AnimeReviewScores
    {
        return $this->scores;
    }

    /**
     * @return string
     */
    public function getReview(): string
    {
        return $this->review;
    }

}
