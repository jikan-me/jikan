<?php

namespace Jikan\Model\Anime;

/**
 * Class AnimeStatsScore
 *
 * @package Jikan\Model\Anime\AnimeStatsScore
 */
class AnimeStatsScore
{
    /**
     * @var int
     */
    private $votes;

    /**
     * @var float
     */
    private $percentage;


    /**
     * @param int $votes
     * @param float $percentage
     * @return AnimeStats
     */
    public static function setProperties(int $votes, float $percentage): AnimeStatsScore
    {
        $instance = new self();

        $instance->votes = $votes;
        $instance->percentage = $percentage;

        return $instance;
    }

    /**
     * @return int
     */
    public function getVotes(): int
    {
        return $this->votes;
    }

    /**
     * @return float
     */
    public function getPercentage(): float
    {
        return $this->percentage;
    }
}
