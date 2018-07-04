<?php

namespace JikanTest\Request;

use Jikan\Jikan;
use Jikan\Request\Search\SearchRequest;
use PHPUnit\Framework\TestCase;

/**
 * Class JikanTest
 */
class SearchRequestTest extends TestCase
{
    /**
     * @var Jikan
     */
    private $jikan;

    public function setUp()
    {
        $this->jikan = new Jikan();
    }

    /**
     * @test
     */
    public function it_denies_invalid_types()
    {
        $request = new SearchRequest('foo', 'bar');
        self::expectException(\InvalidArgumentException::class);
        $request->getPath();
    }

    /**
     * @test
     */
    public function it_accepts_valid_types()
    {
        $request = new SearchRequest('bar', SearchRequest::ANIME);
        $url = $request->getPath();
        self::assertEquals('https://myanimelist.net/anime.php?q=bar', $url);
    }
    /**
     * @test
     */
    public function it_sets_offset()
    {
        $request = new SearchRequest('bar', SearchRequest::ANIME, 1);
        $url = $request->getPath();
        self::assertEquals('https://myanimelist.net/anime.php?q=bar&show=50', $url);
    }
}
