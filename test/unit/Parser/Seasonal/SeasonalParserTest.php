<?php

namespace JikanTest\Parser\Seasonal;

use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

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

        $client = new HttpClientWrapper($this->httpClient);
        $request = new \Jikan\Request\Seasonal\SeasonalRequest(2018, 'spring');
        $crawler = $client->request('GET', $request->getPath());
        $this->springParser = new \Jikan\Parser\Seasonal\SeasonalParser($crawler);
    }

    #[Test]
    public function it_gets_the_season()
    {
        self::assertEquals('Spring 2018', $this->springParser->getSeasonName() . " " . $this->springParser->getSeasonYear());
    }

    #[Test]
    public function it_gets_the_anime()
    {
        $anime = $this->springParser->getSeasonalAnime();
        self::assertCount(290, $anime);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Seasonal\SeasonalAnime::class, $anime);
    }
}
