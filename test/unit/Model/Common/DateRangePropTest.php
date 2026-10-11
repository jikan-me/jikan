<?php

namespace JikanTest\Model\Common;

use Jikan\Model\Common\DateRange;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class DateRangePropTest
 */
class DateRangePropTest extends TestCase
{
    #[Test]
    public function it_preserves_year_only_precision_in_from_prop()
    {
        // jikan#486: a year-only date must not become 'January 1 of the year'.
        $range = new DateRange('1989');

        self::assertNull($range->getFromProp()->getDay());
        self::assertNull($range->getFromProp()->getMonth());
        self::assertEquals(1989, $range->getFromProp()->getYear());
    }

    #[Test]
    public function it_preserves_month_only_precision_in_from_prop()
    {
        $range = new DateRange('May, 1979');

        self::assertNull($range->getFromProp()->getDay());
        self::assertEquals(5, $range->getFromProp()->getMonth());
        self::assertEquals(1979, $range->getFromProp()->getYear());
    }

    #[Test]
    public function it_keeps_full_precision()
    {
        $range = new DateRange('Apr 1, 1998 to Sep 30, 1998');

        self::assertEquals(1, $range->getFromProp()->getDay());
        self::assertEquals(4, $range->getFromProp()->getMonth());
        self::assertEquals(1998, $range->getFromProp()->getYear());

        self::assertEquals(30, $range->getUntilProp()->getDay());
        self::assertEquals(9, $range->getUntilProp()->getMonth());
        self::assertEquals(1998, $range->getUntilProp()->getYear());
    }

    #[Test]
    public function it_returns_null_props_for_missing_or_unknown_dates()
    {
        self::assertNull((new DateRange('Not available'))->getFromProp()->getYear());

        $unknownUntil = new DateRange('Apr 4, 1980 to ?');
        self::assertEquals(1980, $unknownUntil->getFromProp()->getYear());
        self::assertNull($unknownUntil->getUntilProp()->getYear());
    }
}