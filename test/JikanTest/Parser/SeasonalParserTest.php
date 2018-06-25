<?php

use PHPUnit\Framework\TestCase;
use Jikan\Jikan;

/**
 * Class SeasonalParserTest
 */
class SeasonalParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Seasonal
     */
    private $springParser;

    public function setUp()
    {
        $client = new \Goutte\Client();
        $request = new \Jikan\Request\Seasonal(2018, 'spring');
        $crawler = $client->request('GET', $request->getPath());
        $this->springParser = new \Jikan\Parser\Seasonal($crawler);
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_season()
    {
        self::assertEquals('Spring 2018', $this->springParser->getSeason());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_anime()
    {
        $anime = $this->springParser->getSeasonalAnime();
        self::assertCount(234, $anime);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\SeasonalAnime::class, $anime);
    }
}
