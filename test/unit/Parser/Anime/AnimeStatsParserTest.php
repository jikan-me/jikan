<?php

namespace JikanTest\Parser\Anime;

use JikanTest\ModelSnapshot;
use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Anime\AnimeStatsParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

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
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->animeStatsParser = new \Jikan\Parser\Anime\AnimeStatsParser($crawler);
    }

    #[Test]
    public function it_gets_numeric_statistics()
    {
        self::assertGreaterThan(0, $this->animeStatsParser->getWatching());
        self::assertGreaterThan(0, $this->animeStatsParser->getCompleted());
        self::assertGreaterThan(0, $this->animeStatsParser->getOnHold());
        self::assertGreaterThan(0, $this->animeStatsParser->getDropped());
        self::assertGreaterThan(0, $this->animeStatsParser->getTotal());
        self::assertGreaterThan(0, $this->animeStatsParser->getPlanToWatch());
    }

    #[Test]
    public function it_gets_score_attributes()
    {
        self::assertEquals(
            1326,
            $this->animeStatsParser->getScores()[10]->getVotes()
        );
    }

    #[Test]
    public function it_builds_the_same_model_when_parsed_twice(): void
    {
        self::assertEquals($this->animeStatsParser->getModel(), $this->animeStatsParser->getModel());
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->animeStatsParser->getModel());
    }
}
