<?php

namespace Jikan\Helper;

/**
 * Class TimeProvider
 * @package Jikan\Helper
 */
class TimeProvider
{
    /**
     * @return \DateTime
     * @throws \Exception
     */
    public function getDateTime(): \DateTime
    {
        return new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    public function getDateTimeImmutable(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
    }
}
