<?php

namespace Jikan\Model\Common;

use Jikan\Helper\Constants;
use Jikan\Helper\Parser;

/**
 * Class DateRange
 *
 * @package Jikan\Model
 */
class DateRange
{
    /**
     * @var string
     */
    private $date;

    /**
     * DateRange constructor.
     *
     * @param string $date
     */
    public function __construct(string $date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->date;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getFrom(): ?\DateTimeImmutable
    {
        $date = $this->date;
        if ($date === Constants::DISPLAY_NOT_AVAILABLE) {
            return null;
        }
        if (strpos($date, ' to ') !== false || strpos($date, ' to ?') !== false) {
            $date = explode(' to ', $date)[0];
        }

        return Parser::parseDate($date);
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getUntil(): ?\DateTimeImmutable
    {
        $date = $this->date;
        if (strpos($date, ' to ') === false || strpos($date, ' to ?') !== false) {
            return null;
        }
        $date = explode(' to ', $date)[1];

        return Parser::parseDate($date);
    }

    /**
     * @return DateProp
     */
    public function getFromProp(): DateProp
    {
        $date = $this->date;
        if ($date === Constants::DISPLAY_NOT_AVAILABLE) {
            return new DateProp(null);
        }
        if (strpos($date, ' to ') !== false || strpos($date, ' to ?') !== false) {
            $date = explode(' to ', $date)[0];
        }

        return $this->propFrom($date);
    }

    /**
     * @return DateProp
     */
    public function getUntilProp(): DateProp
    {
        if (strpos($this->date, ' to ') === false || strpos($this->date, ' to ?') !== false) {
            return new DateProp(null);
        }

        return $this->propFrom(explode(' to ', $this->date)[1]);
    }

    /**
     * A DateProp built from a RAW date segment so precision survives
     * (jikan#486). DateProp::parse already knows the three precisions — a bare
     * year yields null day/month, 'May, 1979' a null day, 'May 1, 1979' all
     * three. Round-tripping through getFrom()/getUntil() destroyed that by
     * padding a year-only date to 'YYYY-01-01'.
     */
    private function propFrom(string $segment): DateProp
    {
        $segment = trim($segment);

        if ($segment === '' || $segment === '?' || $segment === Constants::DISPLAY_NOT_AVAILABLE) {
            return new DateProp(null);
        }

        return new DateProp($segment);
    }
}
