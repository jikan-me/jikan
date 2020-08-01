<?php

namespace Jikan\Model\Reviews;

use Jikan\Model\Anime\AnimeReviewScores;

/**
 * Class AnimeReview
 *
 * @package Jikan\Model
 */
abstract class AnimeReview extends Review
{

    /**
     * @var string
     */
    protected $episodesWatched;

    /**
     * @var AnimeReviewScores
     */
    protected $scores;

    /**
     * @return string
     */
    public function getEpisodesWatched(): string
    {
        return $this->episodesWatched;
    }

    /**
     * @return AnimeReviewScores
     */
    public function getScores(): AnimeReviewScores
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
