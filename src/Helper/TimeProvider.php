<?php

namespace Jikan\Helper;

/**
 * Class TimeProvider
 * @package Jikan\Helper
 */
class TimeProvider
{
    /**
     * @var string
     */
    private $timezone;

    /**
     * TimeProvider constructor.
     * @param string $timezone
     */
    public function __construct(string $timezone = 'UTC')
    {
        $this->timezone = $timezone;
    }

    /**
     * @return \DateTime
     * @throws \Exception
     */
    public function getDateTime(): \DateTime
    {
        return new \DateTime('now', new \DateTimeZone($this->timezone));
    }

    /**
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    public function getDateTimeImmutable(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now', new \DateTimeZone($this->timezone));
    }
}
