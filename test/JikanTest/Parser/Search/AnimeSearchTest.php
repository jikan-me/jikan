<?php

namespace JikanTest\Parser\Search;

use Jikan\MyAnimeList\MalClient;
use PHPUnit\Framework\TestCase;

/**
 * Class AnimeSearchTest
 */
class AnimeSearchTest extends TestCase
{

    private $search;
    private $anime;

    public function setUp()
    {
        $jikan = new MalClient;
        $this->search = $jikan->getAnimeSearch(
            new \Jikan\Request\Search\AnimeSearchRequest('Fate')
        );
        $this->anime = $this->search->getResults()[0];
    }

    /**
     * @test
     * @vcr AnimeSearchTest.yaml
     */
    public function it_gets_the_title()
    {
        self::assertEquals("Fate/Zero", $this->anime->getTitle());
    }

    /**
     * @test
     * @vcr AnimeSearchTest.yaml
     */
    public function it_gets_the_image_url()
    {
        self::assertEquals("https://myanimelist.cdn-dena.com/images/anime/2/73249.jpg?s=0ddd3d84549e11eda144df33626f97ae", $this->anime->getImageUrl());
    }

    /**
     * @test
     * @vcr AnimeSearchTest.yaml
     */
    public function it_gets_the_airing()
    {
        self::assertEquals($this->anime->isAiring(), false);
    }

    /**
     * @test
     * @vcr AnimeSearchTest.yaml
     */
    public function it_gets_the_synopsis()
    {
        self::assertContains("With the promise of granting any wish, the omnipotent Holy Grail triggered three wars in the past, each too cruel and fierce to leave a victor.", $this->anime->getSynopsis());
    }

    /**
     * @test
     * @vcr AnimeSearchTest.yaml
     */
    public function it_gets_the_type()
    {
        self::assertEquals($this->anime->getType(), "TV");
    }

    /**
     * @test
     * @vcr AnimeSearchTest.yaml
     */
    public function it_gets_the_episodes()
    {
        self::assertEquals($this->anime->getEpisodes(), 13);
    }

    /**
     * @test
     * @vcr AnimeSearchTest.yaml
     */
    public function it_gets_the_start_date()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->anime->getStartDate());
    }

    /**
     * @test
     * @vcr AnimeSearchTest.yaml
     */
    public function it_gets_the_end_date()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->anime->getEndDate());
    }

    /**
     * @test
     * @vcr AnimeSearchTest.yaml
     */
    public function it_gets_the_members()
    {
        self::assertEquals($this->anime->getMembers(), 677392);
    }

    /**
     * @test
     * @vcr AnimeSearchTest.yaml
     */
    public function it_gets_the_rated()
    {
        self::assertEquals($this->anime->getRated(), 'R');
    }

    /**
     * @test
     * @vcr AnimeSearchTest.yaml
     */
    public function it_gets_the_score()
    {
        self::assertEquals($this->anime->getScore(), 8.44);
    }
}
