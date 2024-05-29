<?php

namespace JikanTest\Parser\Common;

use Jikan\Parser\Common\MalUrlParser;
use JikanTest\TestCase;

/**
 * Class MalUrlParserTest
 *
 * @package JikanTest\Parser\Common
 */
class MalUrlParserTest extends TestCase
{
    /**
     * @dataProvider urlProvider
     *
     * @param string $url
     */
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
    public function urlProvider(): array
    {
        return [
            ['https://myanimelist.net/anime/12345'],
            ['https://myanimelist.net/anime/12345/Cowboy_Bebop'],
            ['https://myanimelist.net/anime/producer/12345/Funimation'],
            ['https://myanimelist.net/anime/12345/Cowboy_Bebop/episode/26'],
        ];
    }
}
