<?php

namespace Jikan\Model\Schedule;

use Jikan\Model\Common\AnimeCard;
use Jikan\Parser\Schedule\ScheduleParser;

/**
 * Class Schedule
 *
 * @package Jikan\Model
 */
class Schedule
{
    /**
     * @var AnimeCard[]
     */
    public $monday = [];

    /**
     * @var AnimeCard[]
     */
    public $tuesday = [];

    /**
     * @var AnimeCard[]
     */
    public $wednesday = [];

    /**
     * @var AnimeCard[]
     */
    public $thursday = [];

    /**
     * @var AnimeCard[]
     */
    public $friday = [];

    /**
     * @var AnimeCard[]
     */
    public $saturday = [];

    /**
     * @var AnimeCard[]
     */
    public $sunday = [];

    /**
     * @var AnimeCard[]
     */
    public $other = [];

    /**
     * @var AnimeCard[]
     */
    public $unknown = [];

    /**
     * @param ScheduleParser $parser
     *
     * @return Schedule
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(ScheduleParser $parser): self
    {
        $instance = new self();
        $instance->monday = $parser->getShedule('monday');
        $instance->tuesday = $parser->getShedule('tuesday');
        $instance->wednesday = $parser->getShedule('wednesday');
        $instance->thursday = $parser->getShedule('thursday');
        $instance->friday = $parser->getShedule('friday');
        $instance->saturday = $parser->getShedule('saturday');
        $instance->sunday = $parser->getShedule('sunday');
        $instance->other = $parser->getShedule('other');
        $instance->unknown = $parser->getShedule('unknown');

        return $instance;
    }

    /**
     * @return AnimeCard[]
     */
    public function getMonday(): array
    {
        return $this->monday;
    }

    /**
     * @return AnimeCard[]
     */
    public function getTuesday(): array
    {
        return $this->tuesday;
    }

    /**
     * @return AnimeCard[]
     */
    public function getWednesday(): array
    {
        return $this->wednesday;
    }

    /**
     * @return AnimeCard[]
     */
    public function getThursday(): array
    {
        return $this->thursday;
    }

    /**
     * @return AnimeCard[]
     */
    public function getFriday(): array
    {
        return $this->friday;
    }

    /**
     * @return AnimeCard[]
     */
    public function getSaturday(): array
    {
        return $this->saturday;
    }

    /**
     * @return AnimeCard[]
     */
    public function getSunday(): array
    {
        return $this->sunday;
    }

    /**
     * @return AnimeCard[]
     */
    public function getOther(): array
    {
        return $this->other;
    }

    /**
     * @return AnimeCard[]
     */
    public function getUnknown(): array
    {
        return $this->unknown;
    }
}
