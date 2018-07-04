<?php

namespace Jikan\Model;

use Jikan\Parser\User\Profile\MangaStatsParser;

/**
 * Class MangaStats
 *
 * @package Jikan\Model
 */
class MangaStats
{
    /**
     * @var
     */
    private $mangaVolumesRead;

    /**
     * @var
     */
    private $mangaChaptersRead;

    /**
     * @var
     */
    private $mangaReread;

    /**
     * @var
     */
    private $mangaTotalEntries;

    /**
     * @var
     */
    private $mangaPlanToRead;

    /**
     * @var
     */
    private $mangaDropped;

    /**
     * @var
     */
    private $mangaOnHold;

    /**
     * @var
     */
    private $mangaCompleted;

    /**
     * @var
     */
    private $mangaReading;

    /**
     * @var
     */
    private $mangaMeanScore;

    /**
     * @var
     */
    private $mangaDaysRead;

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
    public function getMangaDaysRead(): float
    {
        return $this->mangaDaysRead;
    }

    /**
     * @return float
     */
    public function getMangaMeanScore(): float
    {
        return $this->mangaMeanScore;
    }

    /**
     * @return int
     */
    public function getMangaReading(): int
    {
        return $this->mangaReading;
    }

    /**
     * @return int
     */
    public function getMangaCompleted(): int
    {
        return $this->mangaCompleted;
    }

    /**
     * @return int
     */
    public function getMangaOnHold(): int
    {
        return $this->mangaOnHold;
    }

    /**
     * @return int
     */
    public function getMangaDropped(): int
    {
        return $this->mangaDropped;
    }

    /**
     * @return int
     */
    public function getMangaPlanToRead(): int
    {
        return $this->mangaPlanToRead;
    }

    /**
     * @return int
     */
    public function getMangaTotalEntries(): int
    {
        return $this->mangaTotalEntries;
    }

    /**
     * @return int
     */
    public function getMangaReread(): int
    {
        return $this->mangaReread;
    }

    /**
     * @return int
     */
    public function getMangaChaptersRead(): int
    {
        return $this->mangaChaptersRead;
    }

    /**
     * @return int
     */
    public function getMangaVolumesRead(): int
    {
        return $this->mangaVolumesRead;
    }
}
