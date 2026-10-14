<?php

namespace JikanTest\Helper;

use Jikan\Helper\Parser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class ParserTest
 */
class ParserTest extends TestCase
{
    #[Test]
    public function it_gets_dates()
    {
        $date = Parser::parseDate(2011);
        self::assertInstanceOf(\DateTimeImmutable::class, $date);
        self::assertEquals('2011-01-01', $date->format('Y-m-d'));

        $date = Parser::parseDate('Dec 1, 2004');
        self::assertInstanceOf(\DateTimeImmutable::class, $date);
        self::assertEquals('2004-12-01', $date->format('Y-m-d'));

        $date = Parser::parseDate('Dec, 2004');
        self::assertInstanceOf(\DateTimeImmutable::class, $date);
        self::assertEquals('2004-12-01', $date->format('Y-m-d'));

        $date = Parser::parseDate('Jul 4, 2021 9:22 PM');
        self::assertInstanceOf(\DateTimeImmutable::class, $date);
        self::assertEquals('2021-07-04 21:22', $date->format('Y-m-d H:i'));

        $date = Parser::parseDate('May 25, 8:15 PM');
        self::assertInstanceOf(\DateTimeImmutable::class, $date);
        self::assertEquals('05-25 20:15', $date->format('m-d H:i'));

        $date = Parser::parseDate('Yesterday, 12:04 PM');
        self::assertInstanceOf(\DateTimeImmutable::class, $date);
        self::assertEquals(1, (new \DateTimeImmutable())->setTime(23, 59)->diff($date)->d);
        self::assertEquals('12:04', $date->format('H:i'));

        $date = Parser::parseDate('9 hours ago');
        self::assertInstanceOf(\DateTimeImmutable::class, $date);
        self::assertEquals(0, (new \DateTimeImmutable())->diff($date)->d);
        self::assertEquals(9, (new \DateTimeImmutable())->diff($date)->h);
        self::assertEquals(0, (new \DateTimeImmutable())->diff($date)->i);

        $date = Parser::parseDate('45 minutes ago');
        self::assertInstanceOf(\DateTimeImmutable::class, $date);
        self::assertEquals(0, (new \DateTimeImmutable())->diff($date)->d);
        self::assertEquals(0, (new \DateTimeImmutable())->diff($date)->h);
        self::assertEquals(45, (new \DateTimeImmutable())->diff($date)->i);

        $date = Parser::parseDate('?');
        self::assertNull($date);
    }

    #[Test]
    public function it_reads_two_digit_years_from_the_previous_century_when_they_are_far_ahead(): void
    {
        // PHP alone reads "61" as 2061: Kaguya-hime (1961) and Ookami Shounen Ken (1963) came back 100 years late.
        self::assertSame('1961-01-01', Parser::parseDateMDY('01-01-61')->format('Y-m-d'));
        self::assertSame('1963-11-25', Parser::parseDateMDY('11-25-63')->format('Y-m-d'));
        self::assertSame('1963-12-21', Parser::parseDateDMY('21-12-63')->format('Y-m-d'));

        // Dates a few years ahead (announced titles) stay in this century, and the 1990s stay as they were.
        $soon = (string) ((int) date('y') + 2);
        self::assertSame('20' . $soon, Parser::parseDateMDY("04-01-$soon")->format('Y'));
        self::assertSame('1998-04-03', Parser::parseDateMDY('04-03-98')->format('Y-m-d'));
    }
}
