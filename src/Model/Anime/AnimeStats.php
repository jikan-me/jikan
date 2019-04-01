<?php

namespace Jikan\Model\Anime;

use Jikan\Parser\Anime\AnimeStatsParser;

/**
 * Class AnimeStats
 *
 * @package Jikan\Model\Anime\Anime
 */
class AnimeStats
{
    /**
     * @var int
     */
    private $watching;

    /**
     * @var int
     */
    private $completed;

    /**
     * @var int
     */
    private $onHold;

    /**
     * @var int
     */
    private $dropped;

    /**
     * @var int
     */
    private $planToWatch;

    /**
     * @var int
     */
    private $total;

    /**
     * @var array
     */
    private $scores;

    /**
     * @param AnimeStatsParser $parser
     *
     * @return AnimeStats
     * @throws \InvalidArgumentException
     */
    public static function fromParser(AnimeStatsParser $parser): AnimeStats
    {
        $instance = new self();
        $instance->watching = $parser->getWatching();
        $instance->completed = $parser->getCompleted();
        $instance->onHold = $parser->getOnHold();
        $instance->dropped = $parser->getDropped();
        $instance->planToWatch = $parser->getPlanToWatch();
        $instance->total = $parser->getTotal();
        $instance->scores = $parser->getScores();

        return $instance;
    }

    /**
     * @return int
     */
    public function getWatching(): int
    {
        return $this->watching;
    }

    /**
     * @return int
     */
    public function getCompleted(): int
    {
        return $this->completed;
    }

    /**
     * @return int
     */
    public function getOnHold(): int
    {
        return $this->onHold;
    }

    /**
     * @return int
     */
    public function getDropped(): int
    {
        return $this->dropped;
    }

    /**
     * @return int
     */
    public function getPlanToWatch(): int
    {
        return $this->planToWatch;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return array
     */
    public function getScores(): array
    {
        return $this->scores;
    }
}
