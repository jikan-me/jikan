<?php

namespace JikanTest\Parser\Manga;

use JikanTest\ModelSnapshot;
use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Manga\MangaStatsParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

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
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->mangaStatsParser = new MangaStatsParser($crawler);
    }

    #[Test]
    public function it_gets_numeric_statistics()
    {
        self::assertGreaterThan(0, $this->mangaStatsParser->getReading());
        self::assertGreaterThan(0, $this->mangaStatsParser->getCompleted());
        self::assertGreaterThan(0, $this->mangaStatsParser->getOnHold());
        self::assertGreaterThan(0, $this->mangaStatsParser->getDropped());
        self::assertGreaterThan(0, $this->mangaStatsParser->getTotal());
        self::assertGreaterThan(0, $this->mangaStatsParser->getPlanToRead());
    }

    #[Test]
    public function it_gets_score_attributes()
    {
        self::assertEquals(
            11045, $this->mangaStatsParser->getScores()[10]->getVotes()
        );
    }

    #[Test]
    public function it_builds_the_same_model_when_parsed_twice(): void
    {
        self::assertEquals($this->mangaStatsParser->getModel(), $this->mangaStatsParser->getModel());
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->mangaStatsParser->getModel());
    }
}
