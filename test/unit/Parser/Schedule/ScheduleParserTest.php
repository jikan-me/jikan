<?php

namespace JikanTest\Parser\Schedule;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Common\AnimeCard;
use Jikan\Parser\Schedule\ScheduleParser;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class ScheduleParserTest
 */
class ScheduleParserTest extends TestCase
{
    /**
     * @var ScheduleParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/season/schedule');
        $this->parser = new ScheduleParser($crawler);
    }

    #[Test]
    public function it_gets_mondays()
    {
        $monday = $this->parser->getShedule('monday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $monday);
        self::assertCount(10, $monday);
    }

    #[Test]
    public function it_gets_tuesdays()
    {
        $tuesday = $this->parser->getShedule('tuesday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $tuesday);
        self::assertCount(9, $tuesday);
    }

    #[Test]
    public function it_gets_wednesdays()
    {
        $wednesday = $this->parser->getShedule('wednesday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $wednesday);
        self::assertCount(10, $wednesday);
    }

    #[Test]
    public function it_gets_thursdays()
    {
        $thursday = $this->parser->getShedule('thursday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $thursday);
        self::assertCount(8, $thursday);
    }

    #[Test]
    public function it_gets_fridays()
    {
        $friday = $this->parser->getShedule('friday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $friday);
        self::assertCount(14, $friday);
    }

    #[Test]
    public function it_gets_saturdays()
    {
        $saturday = $this->parser->getShedule('saturday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $saturday);
        self::assertCount(21, $saturday);
    }

    #[Test]
    public function it_gets_sundays()
    {
        $sunday = $this->parser->getShedule('sunday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $sunday);
        self::assertCount(29, $sunday);
    }

    #[Test]
    public function it_gets_all()
    {
        $all = $this->parser->getShedule('all');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $all);
        self::assertCount(137, $all);
    }

    #[Test]
    public function it_gets_other()
    {
        $other = $this->parser->getShedule('other');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $other);
        self::assertCount(10, $other);
    }

    #[Test]
    public function it_gets_unknown()
    {
        $unknown = $this->parser->getShedule('unknown');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $unknown);
        self::assertCount(17, $unknown);
    }

    #[Test]
    public function it_builds_the_same_model_when_parsed_twice(): void
    {
        self::assertEquals($this->parser->getModel(), $this->parser->getModel());
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->parser->getModel(), true);
    }
}
