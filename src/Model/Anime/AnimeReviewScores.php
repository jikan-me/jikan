<?php

namespace Jikan\Model\Anime;

/**
 * Class AnimeReviewScores
 *
 * MyAnimeList's review redesign (2022) dropped the per-category ratings; a review now shows one overall
 * "Reviewer's Rating". The category getters stay for compatibility and return null.
 *
 * @package Jikan\Model
 */
class AnimeReviewScores
{
    private int $overall;

    public static function fromOverall(int $overall): AnimeReviewScores
    {
        $instance = new self();
        $instance->overall = $overall;

        return $instance;
    }

    /**
     * The reviewer's overall rating, 1 to 10.
     */
    public function getOverall(): int
    {
        return $this->overall;
    }

    /**
     * @return null MyAnimeList no longer shows category ratings.
     */
    public function getStory(): ?int
    {
        return null;
    }

    /**
     * @return null MyAnimeList no longer shows category ratings.
     */
    public function getAnimation(): ?int
    {
        return null;
    }

    /**
     * @return null MyAnimeList no longer shows category ratings.
     */
    public function getSound(): ?int
    {
        return null;
    }

    /**
     * @return null MyAnimeList no longer shows category ratings.
     */
    public function getCharacter(): ?int
    {
        return null;
    }

    /**
     * @return null MyAnimeList no longer shows category ratings.
     */
    public function getEnjoyment(): ?int
    {
        return null;
    }
}
