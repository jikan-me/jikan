<?php

namespace JikanTest\Parser\Person;

use Goutte\Client;
use Jikan\Model\AnimeCard;
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
        self::assertContains('Shingeki no Kyojin Season 3', $monday);
        self::assertContains('Chara to Otamajakushi Shima', $monday);
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
        self::assertContains('Black Clover', $tuesday);
        self::assertContains('Uchi no Oochopus', $tuesday);
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
        self::assertContains('Yu☆Gi☆Oh! VRAINS', $wednesday);
        self::assertContains('Chuukan Kanriroku Tonegawa', $wednesday);
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
        self::assertContains('Steins;Gate 0', $thursday);
        self::assertContains('Ice Kuritarou', $thursday);
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
        self::assertContains('Satsuriku no Tenshi', $friday);
        self::assertContains('Aware! Meisaku-kun', $friday);
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
        self::assertContains('Boku no Hero Academia 3rd Season', $saturday);
        self::assertContains('Pikachin-Kit', $saturday);
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
        self::assertContains('One Piece', $sunday);
        self::assertContains('Caribadix 2nd Season', $sunday);
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
        self::assertContains('Shingeki no Kyojin Season 3', $all);
        self::assertContains('Washimo 6th Season', $all);
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
        self::assertContains('Gudetama', $other);
        self::assertContains('Yowamushi Monsters', $other);
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
        self::assertContains('FLCL Progressive', $unknown);
        self::assertContains('Washimo 6th Season', $unknown);
    }
}
