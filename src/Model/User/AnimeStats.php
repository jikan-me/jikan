<?php

namespace Jikan\Model\User;

use Jikan\Parser\User\Profile\AnimeStatsParser;

/**
 * Class AnimeStats
 *
 * @package Jikan\Model
 */
class AnimeStats
{
    /**
     * @var float
     */
    private $daysWatched;

    /**
     * @var float
     */
    private $meanScore;

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
    private $totalEntries;

    /**
     * @var int
     */
    private $rewatched;

    /**
     * @var int
     */
    private $episodesWatched;

    /**
     * @param AnimeStatsParser $parser
     *
     * @return AnimeStats
     * @throws \InvalidArgumentException
     */
    public static function fromParser(AnimeStatsParser $parser): self
    {
        $instance = new self();
        $instance->daysWatched = $parser->getDaysWatched();
        $instance->meanScore = $parser->getMeanScore();
        $instance->watching = $parser->getWatching();
        $instance->completed = $parser->getCompleted();
        $instance->onHold = $parser->getOnHold();
        $instance->dropped = $parser->getDropped();
        $instance->planToWatch = $parser->getPlanToWatch();
        $instance->totalEntries = $parser->getTotalEntries();
        $instance->rewatched = $parser->getRewatched();
        $instance->episodesWatched = $parser->getEpisodesWatched();


        return $instance;
    }

    /**
     * @return float
     */
    public function getDaysWatched(): float
    {
        return $this->daysWatched;
    }

    /**
     * @return float
     */
    public function getMeanScore(): float
    {
        return $this->meanScore;
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
    public function getTotalEntries(): int
    {
        return $this->totalEntries;
    }

    /**
     * @return int
     */
    public function getRewatched(): int
    {
        return $this->rewatched;
    }

    /**
     * @return int
     */
    public function getEpisodesWatched(): int
    {
        return $this->episodesWatched;
    }
}
