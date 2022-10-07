<?php

namespace JikanTest\Parser\Anime;

use Jikan\Parser\Anime\AnimeStatsParser;
use JikanTest\TestCase;

/**
 * Class AnimeStatsParserTest
 */
class AnimeStatsParserTest extends TestCase
{
    /**
     * @var AnimeStatsParser
     */
    private $animeStatsParser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Anime\AnimeStatsRequest(37405);
        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->animeStatsParser = new \Jikan\Parser\Anime\AnimeStatsParser($crawler);
    }

    /**
     * @test
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
     */
    public function it_gets_score_attributes()
    {
        self::assertEquals(
            1062,
            $this->animeStatsParser->getScores()[10]->getVotes()
        );
    }
}
