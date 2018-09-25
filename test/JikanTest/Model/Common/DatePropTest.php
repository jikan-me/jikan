<?php

namespace JikanTest\Model\Common;

use Jikan\Model\Common\DateProp;
use PHPUnit\Framework\TestCase;

/**
 * Class DatePropTest
 */
class DatePropTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_date_props()
    {
        $date = DateProp::fromFactory(null, null, null);
        self::assertInstanceOf(DateProp::class, $date);
        self::assertEquals(null, $date->getDay());

        $date = new DateProp('Dec 1, 2004');
        self::assertInstanceOf(DateProp::class, $date);
        self::assertEquals(1, $date->getDay());
        self::assertEquals(12, $date->getMonth());
        self::assertEquals(2004, $date->getYear());

        $date = new DateProp('Dec, 2004');
        self::assertInstanceOf(DateProp::class, $date);
        self::assertEquals(null, $date->getDay());
        self::assertEquals(12, $date->getMonth());
        self::assertEquals(2004, $date->getYear());
    }
}
