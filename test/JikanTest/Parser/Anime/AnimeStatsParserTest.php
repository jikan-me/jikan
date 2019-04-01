<?php

namespace JikanTest\Parser\Anime;

use Jikan\Model\Anime\AnimeStatsScore;
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
    public function it_gets_score_attributes()
    {
        self::assertEquals(
            126, $this->animeStatsParser->getScores()[10]->getVotes()
        );
    }
}
