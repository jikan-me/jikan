<?php

namespace JikanTest\Helper;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use PHPUnit\Framework\TestCase;

/**
 * Class JStringTest
 */
class JStringTest extends TestCase
{
    /**
     * @test
     * @dataProvider  stringFloatProvider
     */
    public function it_checks_for_string_float(bool $given, bool $expected): void
    {
        self::assertSame($expected, $given);
    }

    public function stringFloatProvider(): array
    {
        return [
            [JString::isStringFloat('3.123'), true],
            [JString::isStringFloat(' 3.123'), true],
            [JString::isStringFloat(' abc 3.123'), false],
            [JString::isStringFloat('3..123'), false],
        ];
    }

    /**
     * @test
     */
    public function it_converts_string_to_canonical_format()
    {
        self::assertEquals(
            'Asuka_Monthly',
            JString::strToCanonical('Asuka (Monthly)')
        );

        self::assertEquals(
            '5pb',
            JString::strToCanonical('5pb.')
        );

        self::assertEquals(
            '3xCube',
            JString::strToCanonical('3xCube')
        );

        self::assertEquals(
            '4Kids_Entertainment',
            JString::strToCanonical('4Kids Entertainment')
        );

        self::assertEquals(
            '1st_PLACE',
            JString::strToCanonical('1st PLACE')
        );

        self::assertEquals(
            '81_Produce',
            JString::strToCanonical('81 Produce')
        );

        self::assertEquals(
            'A-1_Pictures',
            JString::strToCanonical('A-1 Pictures')
        );

        self::assertEquals(
            'AXsiZ',
            JString::strToCanonical('AXsiZ')
        );

        self::assertEquals(
            'FMF',
            JString::strToCanonical('F.M.F')
        );

        self::assertEquals(
            'U_M_A_A_Inc',
            JString::strToCanonical('U/M/A/A Inc.')
        );

        self::assertEquals(
            'ZIZ_Entertainment_ZIZ',
            JString::strToCanonical('ZIZ Entertainment (ZIZ)')
        );

        self::assertEquals(
            'hack__GU_The_World',
            JString::strToCanonical('.hack//G.U. The World')
        );

        self::assertEquals(
            'Comma',
            JString::strToCanonical('C\'omma')
        );
    }
}
