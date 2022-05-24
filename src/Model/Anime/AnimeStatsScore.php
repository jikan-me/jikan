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
     * @var
     */
    private $score;

    /**
     * @var int
     */
    private $votes;

    /**
     * @var float
     */
    private $percentage;


    /**
     * @param  int   $votes
     * @param  float $percentage
     * @return AnimeStats
     */
    public static function setProperties(int $score, int $votes, float $percentage): AnimeStatsScore
    {
        $instance = new self();

        $instance->score = $score;
        $instance->votes = $votes;
        $instance->percentage = $percentage;

        return $instance;
    }

    /**
     * @return mixed
     */
    public function getScore()
    {
        return $this->score;
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
