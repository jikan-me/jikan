<?php

namespace JikanTest\Parser\Manga;

use Jikan\Parser\Manga\MangaStatsParser;
use PHPUnit\Framework\TestCase;

/**
 * Class MangaStatsParserTest
 */
class MangaStatsParserTest extends TestCase
{
    /**
     * @var MangaStatsParser
     */
    private $mangaStatsParser;

    public function setUp()
    {
        $request = new \Jikan\Request\Manga\MangaStatsRequest(99314);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->mangaStatsParser = new MangaStatsParser($crawler);
    }

    /**
     * @test
     * @vcr MangaStats.yaml
     */
    public function it_gets_numeric_statistics()
    {
        self::assertGreaterThan(0, $this->mangaStatsParser->getReading());
        self::assertGreaterThan(0, $this->mangaStatsParser->getCompleted());
        self::assertGreaterThan(0, $this->mangaStatsParser->getOnHold());
        self::assertGreaterThan(0, $this->mangaStatsParser->getDropped());
        self::assertGreaterThan(0, $this->mangaStatsParser->getTotal());
        self::assertGreaterThan(0, $this->mangaStatsParser->getPlanToRead());
    }

    /**
     * @test
     * @vcr MangaStats.yaml
     */
    public function it_gets_score_statistics()
    {
        self::assertEquals(2, count($this->mangaStatsParser->getScores()[10]));
        self::assertEquals(2, count($this->mangaStatsParser->getScores()[9]));
        self::assertEquals(2, count($this->mangaStatsParser->getScores()[8]));
        self::assertEquals(2, count($this->mangaStatsParser->getScores()[7]));
        self::assertEquals(2, count($this->mangaStatsParser->getScores()[6]));
        self::assertEquals(2, count($this->mangaStatsParser->getScores()[5]));
        self::assertEquals(2, count($this->mangaStatsParser->getScores()[4]));
        self::assertEquals(2, count($this->mangaStatsParser->getScores()[3]));
        self::assertEquals(2, count($this->mangaStatsParser->getScores()[2]));
        self::assertEquals(2, count($this->mangaStatsParser->getScores()[1]));
    }
}
