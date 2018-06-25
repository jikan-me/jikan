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
    private $parser;

    public function setUp()
    {
        $request = new \Jikan\Request\Seasonal(2018, 'summer');
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Seasonal($crawler);
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_season()
    {
        self::assertEquals('Summer 2018', $this->parser->getSeason());
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_anime()
    {
        $anime = $this->parser->getSeasonalAnime();
        self::assertCount(91, $anime);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\SeasonalAnime::class, $anime);
    }
}
