<?php

namespace Jikan\Model\User;

use Jikan\Parser\User\Profile\MangaStatsParser;

/**
 * Class MangaStats
 *
 * @package Jikan\Model
 */
class MangaStats
{
    /**
     * @var float
     */
    private $daysRead;

    /**
     * @var float
     */
    private $meanScore;

    /**
     * @var int
     */
    private $reading;

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
    private $planToRead;

    /**
     * @var int
     */
    private $totalEntries;

    /**
     * @var int
     */
    private $reread;

    /**
     * @var int
     */
    private $chaptersRead;

    /**
     * @var int
     */
    private $volumesRead;

    /**
     * @param MangaStatsParser $parser
     *
     * @return MangaStats
     * @throws \InvalidArgumentException
     */
    public static function fromParser(MangaStatsParser $parser): MangaStats
    {
        $instance = new self();
        $instance->daysRead = $parser->getDaysRead();
        $instance->meanScore = $parser->getMeanScore();
        $instance->reading = $parser->getReading();
        $instance->completed = $parser->getCompleted();
        $instance->onHold = $parser->getOnHold();
        $instance->dropped = $parser->getDropped();
        $instance->planToRead = $parser->getPlanToRead();
        $instance->totalEntries = $parser->getTotalEntries();
        $instance->reread = $parser->getReread();
        $instance->chaptersRead = $parser->getChaptersRead();
        $instance->volumesRead = $parser->getVolumesRead();

        return $instance;
    }

    /**
     * @return float
     */
    public function getDaysRead(): float
    {
        return $this->daysRead;
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
    public function getReading(): int
    {
        return $this->reading;
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
    public function getPlanToRead(): int
    {
        return $this->planToRead;
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
    public function getReread(): int
    {
        return $this->reread;
    }

    /**
     * @return int
     */
    public function getChaptersRead(): int
    {
        return $this->chaptersRead;
    }

    /**
     * @return int
     */
    public function getVolumesRead(): int
    {
        return $this->volumesRead;
    }
}
