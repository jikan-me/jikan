<?php

namespace JikanTest\Parser\Seasonal;

use JikanTest\TestCase;

/**
 * Class SeasonalParserTest
 */
class SeasonalParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Seasonal\SeasonalParser
     */
    private $springParser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new \Goutte\Client($this->httpClient);
        $request = new \Jikan\Request\Seasonal\SeasonalRequest(2018, 'spring');
        $crawler = $client->request('GET', $request->getPath());
        $this->springParser = new \Jikan\Parser\Seasonal\SeasonalParser($crawler);
    }

    /**
     * @test
     */
    public function it_gets_the_season()
    {
        self::assertEquals('Spring 2018', $this->springParser->getSeasonName() . " " . $this->springParser->getSeasonYear());
    }

    /**
     * @test
     */
    public function it_gets_the_anime()
    {
        $anime = $this->springParser->getSeasonalAnime();
        self::assertCount(290, $anime);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Seasonal\SeasonalAnime::class, $anime);
    }
}
