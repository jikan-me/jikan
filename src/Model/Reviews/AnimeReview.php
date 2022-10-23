<?php

namespace Jikan\Model\Reviews;

use Jikan\Model\Anime\AnimeReviewScores;

/**
 * Class UserAnimeReview
 *
 * @package Jikan\Model
 */
abstract class AnimeReview extends Review
{
    /**
     * @var int|null
     */
    protected ?int $episodesWatched;

    /**
     * @return int|null
     */
    public function getEpisodesWatched(): ?int
    {
        return $this->episodesWatched;
    }
}
