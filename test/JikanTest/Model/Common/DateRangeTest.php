<?php

namespace JikanTest\Model\Common;

use Jikan\Model\Common\DateProp;
use Jikan\Model\Common\DateRange;
use PHPUnit\Framework\TestCase;

/**
 * Class DateRangeTest
 */
class DateRangeTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_date()
    {
        $date = new DateRange('Sep 15, 2018 to Sep, 2019');
        self::assertInstanceOf(\DateTimeImmutable::class, $date->getFrom());
        self::assertInstanceOf(\DateTimeImmutable::class, $date->getUntil());

        $date = new DateRange('Sep 15, 2018 to ?');
        self::assertInstanceOf(\DateTimeImmutable::class, $date->getFrom());
        self::assertEquals(null, $date->getUntil());

        $date = new DateRange('?');
        self::assertEquals(null, $date->getFrom());
        self::assertEquals(null, $date->getUntil());
    }

    /**
     * @test
     */
    public function it_gets_date_props()
    {
        $date = new DateRange('Sep 15, 2018 to Oct, 2019');

        self::assertInstanceOf(DateProp::class, $date->getFromProp());
        self::assertInstanceOf(DateProp::class, $date->getUntilProp());

        self::assertEquals(15, $date->getFromProp()->getDay());
        self::assertEquals(9, $date->getFromProp()->getMonth());
        self::assertEquals(2018, $date->getFromProp()->getYear());
        self::assertEquals(1, $date->getUntilProp()->getDay());
        self::assertEquals(10, $date->getUntilProp()->getMonth());
        self::assertEquals(2019, $date->getUntilProp()->getYear());

        $date = new DateRange('Jan, 2018 to ?');
        self::assertEquals(1, $date->getFromProp()->getDay());
        self::assertEquals(1, $date->getFromProp()->getMonth());
        self::assertEquals(2018, $date->getFromProp()->getYear());
        self::assertEquals(null, $date->getUntilProp()->getDay());
        self::assertEquals(null, $date->getUntilProp()->getMonth());
        self::assertEquals(null, $date->getUntilProp()->getYear());

        $date = new DateRange('?');
        self::assertEquals(null, $date->getFromProp()->getDay());
        self::assertEquals(null, $date->getFromProp()->getMonth());
        self::assertEquals(null, $date->getFromProp()->getYear());
        self::assertEquals(null, $date->getUntilProp()->getDay());
        self::assertEquals(null, $date->getUntilProp()->getMonth());
        self::assertEquals(null, $date->getUntilProp()->getYear());

        $date = new DateRange('Jan 3, 2015 to 2016');
        self::assertEquals(1, $date->getUntilProp()->getDay());
        self::assertEquals(1, $date->getUntilProp()->getMonth());
        self::assertEquals(2016, $date->getUntilProp()->getYear());


    }
}
