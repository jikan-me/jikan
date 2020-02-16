<?php

namespace Jikan\Model\Common;

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
        if ($date === 'Not available') {
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
        $date = $this->getFrom();

        return DateProp::fromDateTime($date);
    }

    /**
     * @return DateProp
     */
    public function getUntilProp(): DateProp
    {
        $date = $this->getUntil();

        return DateProp::fromDateTime($date);
    }
}
