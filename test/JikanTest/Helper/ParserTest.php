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

        $date = Parser::parseDate('?');
        self::assertNull($date);
    }
}
