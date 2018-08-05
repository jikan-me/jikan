<?php

namespace JikanTest\Parser\Anime;

use Jikan\Parser\Anime\AnimeStatsParser;
use PHPUnit\Framework\TestCase;

/**
 * Class AnimeStatsParserTest
 */
class AnimeStatsParserTest extends TestCase
{
    /**
     * @var AnimeStatsParser
     */
    private $animeStatsParser;

    public function setUp()
    {
        $request = new \Jikan\Request\Anime\AnimeStatsRequest(37405);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->animeStatsParser = new \Jikan\Parser\Anime\AnimeStatsParser($crawler);
    }

    /**
     * @test
     * @vcr AnimeStats.yaml
     */
    public function it_gets_numeric_statistics()
    {
        self::assertGreaterThan(0, $this->animeStatsParser->getWatching());
        self::assertGreaterThan(0, $this->animeStatsParser->getCompleted());
        self::assertGreaterThan(0, $this->animeStatsParser->getOnHold());
        self::assertGreaterThan(0, $this->animeStatsParser->getDropped());
        self::assertGreaterThan(0, $this->animeStatsParser->getTotal());
        self::assertGreaterThan(0, $this->animeStatsParser->getPlanToWatch());
    }

    /**
     * @test
     * @vcr AnimeStats.yaml
     */
    public function it_gets_score_statistics()
    {
        self::assertEquals(2, count($this->animeStatsParser->getScores()[10]));
        self::assertEquals(2, count($this->animeStatsParser->getScores()[9]));
        self::assertEquals(2, count($this->animeStatsParser->getScores()[8]));
        self::assertEquals(2, count($this->animeStatsParser->getScores()[7]));
        self::assertEquals(2, count($this->animeStatsParser->getScores()[6]));
        self::assertEquals(2, count($this->animeStatsParser->getScores()[5]));
        self::assertEquals(2, count($this->animeStatsParser->getScores()[4]));
        self::assertEquals(2, count($this->animeStatsParser->getScores()[3]));
        self::assertEquals(2, count($this->animeStatsParser->getScores()[2]));
        self::assertEquals(2, count($this->animeStatsParser->getScores()[1]));
    }
}
