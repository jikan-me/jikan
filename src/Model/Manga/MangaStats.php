<?php

namespace Jikan\Model\Manga;

use Jikan\Parser\Manga\MangaStatsParser;

/**
 * Class MangaStats
 *
 * @package Jikan\Model\Anime\Anime
 */
class MangaStats
{
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
    private $total;

    /**
     * @var array
     */
    private $scores;

    /**
     * @param MangaStatsParser $parser
     *
     * @return MangaStats
     * @throws \InvalidArgumentException
     */
    public static function fromParser(MangaStatsParser $parser): MangaStats
    {
        $instance = new self();
        $instance->reading = $parser->getReading();
        $instance->completed = $parser->getCompleted();
        $instance->onHold = $parser->getOnHold();
        $instance->dropped = $parser->getDropped();
        $instance->planToRead = $parser->getPlanToRead();
        $instance->total = $parser->getTotal();
        $instance->scores = $parser->getScores();

        return $instance;
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
