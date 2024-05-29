<?php

namespace JikanTest\Parser\Manga;

use Jikan\Model\Manga\MangaStatsScore;
use Jikan\Parser\Manga\MangaStatsParser;
use JikanTest\TestCase;

/**
 * Class MangaStatsParserTest
 */
class MangaStatsParserTest extends TestCase
{
    /**
     * @var MangaStatsParser
     */
    private $mangaStatsParser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Manga\MangaStatsRequest(99314);
        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->mangaStatsParser = new MangaStatsParser($crawler);
    }

    /**
     * @test
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
     */
    public function it_gets_score_attributes()
    {
        self::assertEquals(
            8404, $this->mangaStatsParser->getScores()[10]->getVotes()
        );
    }
}
