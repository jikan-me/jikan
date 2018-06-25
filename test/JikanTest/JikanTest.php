<?php

use Jikan\Jikan;
use PHPUnit\Framework\TestCase;

/**
 * Class JikanTest
 */
class JikanTest extends TestCase
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
     * @vcr JikanTest_it_gets_anime.yaml
     */
    public function it_gets_anime()
    {
        $anime = $this->jikan->Anime(new \Jikan\Request\Anime(21));
        self::assertInstanceOf(\Jikan\Model\Anime::class, $anime);
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_seasonal_anime()
    {
        $seasonal = $this->jikan->getSeasonal(new \Jikan\Request\Seasonal(2018, 'summer'));
        self::assertInstanceOf(\Jikan\Model\Seasonal::class, $seasonal);
        self::assertCount(91, $seasonal->getAnime());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\SeasonalAnime::class, $seasonal->getAnime());
    }
}
