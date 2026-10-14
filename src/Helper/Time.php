<?php

namespace Jikan\Helper;

use Symfony\Component\Clock\Clock;
use Symfony\Component\Clock\DatePoint;

/**
 * The one place Jikan reads the current time.
 *
 * MyAnimeList shows relative and partial dates ("Yesterday, 9:25 PM", "5 hours ago", "Oct 13", a weekday),
 * which PHP resolves against the current time. Going through the PSR-20 clock of symfony/clock lets tests
 * (and consumers) pin "now" with Clock::set(new MockClock(...)); by default it is the system clock, so
 * behaviour is unchanged.
 */
final class Time
{
    /**
     * Same result as `new \DateTimeImmutable($text, $timezone)`, but relative text is resolved against the clock.
     *
     * @throws \Exception when the text is not a date PHP understands, exactly as the constructor would
     */
    public static function parse(string $text, ?\DateTimeZone $timezone = null): \DateTimeImmutable
    {
        // "" and "now" both mean the current time to the DateTimeImmutable constructor; DatePoint rejects "".
        if (in_array(strtolower(trim($text)), ['', 'now'], true)) {
            return self::now($timezone);
        }

        $timezone ??= self::now()->getTimezone();
        $point = \DateTimeImmutable::createFromInterface(new DatePoint($text, $timezone));

        // DatePoint resolves through modify(), which keeps the clock's microseconds when the text sets a time
        // without a fraction ("Oct 13, 12:00"); the constructor zeroes them. Match it, or two parses differ.
        if ('000000' === new \DateTimeImmutable($text, $timezone)->format('u')) {
            $point = $point->setTime((int) $point->format('H'), (int) $point->format('i'), (int) $point->format('s'), 0);
        }

        return $point;
    }

    public static function now(?\DateTimeZone $timezone = null): \DateTimeImmutable
    {
        $now = \DateTimeImmutable::createFromInterface(Clock::get()->now());

        return $timezone === null ? $now : $now->setTimezone($timezone);
    }
}
