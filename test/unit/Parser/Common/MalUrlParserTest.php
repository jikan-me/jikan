<?php

namespace JikanTest\Parser\Common;

use Jikan\Parser\Common\MalUrlParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class MalUrlParserTest
 *
 * @package unit\Parser\Common
 */
class MalUrlParserTest extends TestCase
{
    #[Test]
    #[DataProvider('urlProvider')]
    public function testMalIdParser(string $url)
    {
        $this->assertEquals(12345, MalUrlParser::parseId($url));
    }


    public function testMalIdParserException()
    {
//        $this->expectException(\RuntimeException::class);
//        MalUrlParser::parseId('https://myanimelist.net/anime/bla');
        $this->assertEquals(0, MalUrlParser::parseId('https://myanimelist.net/anime/blah'));
    }

    /**
     * @return array
     */
    public static function urlProvider(): array
    {
        return [
            ['https://myanimelist.net/anime/12345'],
            ['https://myanimelist.net/anime/12345/Cowboy_Bebop'],
            ['https://myanimelist.net/anime/producer/12345/Funimation'],
            ['https://myanimelist.net/anime/12345/Cowboy_Bebop/episode/26'],
        ];
    }
}
