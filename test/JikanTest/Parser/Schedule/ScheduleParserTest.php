<?php

namespace JikanTest\Parser\Person;

use Goutte\Client;
use Jikan\Model\Common\AnimeCard;
use Jikan\Parser\Schedule\ScheduleParser;
use JikanTest\TestCase;

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

        $client = new Client($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/season/schedule');
        $this->parser = new ScheduleParser($crawler);
    }

    /**
     * @test
     */
    public function it_gets_mondays()
    {
        $monday = $this->parser->getShedule('monday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $monday);
        self::assertCount(9, $monday);
    }

    /**
     * @test
     */
    public function it_gets_tuesdays()
    {
        $tuesday = $this->parser->getShedule('tuesday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $tuesday);
        self::assertCount(5, $tuesday);
    }

    /**
     * @test
     */
    public function it_gets_wednesdays()
    {
        $wednesday = $this->parser->getShedule('wednesday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $wednesday);
        self::assertCount(11, $wednesday);
    }

    /**
     * @test
     */
    public function it_gets_thursdays()
    {
        $thursday = $this->parser->getShedule('thursday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $thursday);
        self::assertCount(6, $thursday);
    }

    /**
     * @test
     */
    public function it_gets_fridays()
    {
        $friday = $this->parser->getShedule('friday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $friday);
        self::assertCount(9, $friday);
    }

    /**
     * @test
     */
    public function it_gets_saturdays()
    {
        $saturday = $this->parser->getShedule('saturday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $saturday);
        self::assertCount(14, $saturday);
    }

    /**
     * @test
     */
    public function it_gets_sundays()
    {
        $sunday = $this->parser->getShedule('sunday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $sunday);
        self::assertCount(21, $sunday);
    }

    /**
     * @test
     */
    public function it_gets_all()
    {
        $all = $this->parser->getShedule('all');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $all);
        self::assertCount(115, $all);
    }

    /**
     * @test
     */
    public function it_gets_other()
    {
        $other = $this->parser->getShedule('other');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $other);
        self::assertCount(10, $other);
    }

    /**
     * @test
     */
    public function it_gets_unknown()
    {
        $unknown = $this->parser->getShedule('unknown');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $unknown);
        self::assertCount(21, $unknown);
    }
}
