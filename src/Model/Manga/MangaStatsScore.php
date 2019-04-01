<?php

namespace Jikan\Model\Manga;

/**
 * Class MangaStatsScore
 *
 * @package Jikan\Model\Manga\MangaStatsScore
 */
class MangaStatsScore
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
     * @return MangaStatsScore
     */
    public static function setProperties(int $votes, float $percentage): MangaStatsScore
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
