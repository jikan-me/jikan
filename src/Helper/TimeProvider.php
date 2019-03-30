<?php

namespace Jikan\Helper;

/**
 * Class TimeProvider
 * @package Jikan\Helper
 */
class TimeProvider
{
    /**
     * @param string $date
     * @param string $tz
     * @return \DateTime
     * @throws \Exception
     */
    public function getDateTime(string $date = 'now', string $tz = 'UTC'): \DateTime
    {
        return new \DateTime($date, new \DateTimeZone($tz));
    }

    /**
     * @param string $date
     * @param string $tz
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    public function getDateTimeImmutable(string $date = 'now', string $tz = 'UTC'): \DateTimeImmutable
    {
        return new \DateTimeImmutable($date, new \DateTimeZone($tz));
    }
}
