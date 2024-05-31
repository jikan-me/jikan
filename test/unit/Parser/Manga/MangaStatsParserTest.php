<?php

namespace JikanTest\Parser\Manga;

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
            8404, $this->mangaStatsParser->getScores()[10]->getVotes()
        );
    }
}
