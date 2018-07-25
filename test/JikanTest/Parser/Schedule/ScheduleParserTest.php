<?php

namespace JikanTest\Parser\Person;

use Goutte\Client;
use Jikan\Model\Common\AnimeCard;
use Jikan\Parser\Schedule\ScheduleParser;
use PHPUnit\Framework\TestCase;

/**
 * Class ScheduleParserTest
 */
class ScheduleParserTest extends TestCase
{
    /**
     * @var ScheduleParser
     */
    private $parser;

    public function setUp()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/season/schedule');
        $this->parser = new ScheduleParser($crawler);
    }

    /**
     * @test
     * @vcr ScheduleParserTest.yaml
     */
    public function it_gets_mondays()
    {
        $monday = $this->parser->getShedule('monday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $monday);
        self::assertCount(12, $monday);
    }

    /**
     * @test
     * @vcr ScheduleParserTest.yaml
     */
    public function it_gets_tuesdays()
    {
        $tuesday = $this->parser->getShedule('tuesday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $tuesday);
        self::assertCount(11, $tuesday);
    }

    /**
     * @test
     * @vcr ScheduleParserTest.yaml
     */
    public function it_gets_wednesdays()
    {
        $wednesday = $this->parser->getShedule('wednesday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $wednesday);
        self::assertCount(5, $wednesday);
    }

    /**
     * @test
     * @vcr ScheduleParserTest.yaml
     */
    public function it_gets_thursdays()
    {
        $thursday = $this->parser->getShedule('thursday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $thursday);
        self::assertCount(11, $thursday);
    }

    /**
     * @test
     * @vcr ScheduleParserTest.yaml
     */
    public function it_gets_fridays()
    {
        $friday = $this->parser->getShedule('friday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $friday);
        self::assertCount(15, $friday);
    }

    /**
     * @test
     * @vcr ScheduleParserTest.yaml
     */
    public function it_gets_saturdays()
    {
        $saturday = $this->parser->getShedule('saturday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $saturday);
        self::assertCount(14, $saturday);
    }

    /**
     * @test
     * @vcr ScheduleParserTest.yaml
     */
    public function it_gets_sundays()
    {
        $sunday = $this->parser->getShedule('sunday');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $sunday);
        self::assertCount(18, $sunday);
    }

    /**
     * @test
     * @vcr ScheduleParserTest.yaml
     */
    public function it_gets_all()
    {
        $all = $this->parser->getShedule('all');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $all);
        self::assertCount(148, $all);
    }

    /**
     * @test
     * @vcr ScheduleParserTest.yaml
     */
    public function it_gets_other()
    {
        $other = $this->parser->getShedule('other');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $other);
        self::assertCount(17, $other);
    }

    /**
     * @test
     * @vcr ScheduleParserTest.yaml
     */
    public function it_gets_unknown()
    {
        $unknown = $this->parser->getShedule('unknown');
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $unknown);
        self::assertCount(36, $unknown);
    }
}
