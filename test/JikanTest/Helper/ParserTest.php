<?php

namespace JikanTest\Helper;

use Jikan\Helper\Parser;
use PHPUnit\Framework\TestCase;

/**
 * Class ParserTest
 */
class ParserTest extends TestCase
{
    /**
     * @test
     */
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
}
