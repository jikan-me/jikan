<?php

namespace JikanTest\Parser\Search;

use Jikan\MyAnimeList\MalClient;
use JikanTest\TestCase;

/**
 * Class AnimeSearchTest
 */
class AnimeSearchTest extends TestCase
{

    private $search;
    private $anime;

    public function setUp(): void
    {
        parent::setUp();

        $jikan = new MalClient($this->httpClient);
        $this->search = $jikan->getAnimeSearch(
            new \Jikan\Request\Search\AnimeSearchRequest('Fate')
        );
        $this->anime = $this->search->getResults()[0];
    }

    /**
     * @test
     */
    public function it_gets_the_title()
    {
        self::assertEquals("Fate/Zero 2nd Season", $this->anime->getTitle());
    }

    /**
     * @test
     */
    public function it_gets_the_image_url()
    {
        self::assertEquals(
            "https://cdn.myanimelist.net/images/anime/1522/117645.jpg?s=e54dcf99dc0874bc5b228ebe8496c857",
            $this->anime->getImages()->getJpg()->getImageUrl()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_airing()
    {
        self::assertEquals($this->anime->isAiring(), false);
    }

    /**
     * @test
     */
    public function it_gets_the_synopsis()
    {
        self::assertStringContainsString(
            "As the Fourth Holy Grail War rages on with no clear victor in sight,",
            $this->anime->getSynopsis()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_type()
    {
        self::assertEquals($this->anime->getType(), "TV");
    }

    /**
     * @test
     */
    public function it_gets_the_episodes()
    {
        self::assertEquals(12, $this->anime->getEpisodes());
    }

    /**
     * @test
     */
    public function it_gets_the_start_date()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->anime->getStartDate());
    }

    /**
     * @test
     */
    public function it_gets_the_end_date()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->anime->getEndDate());
    }

    /**
     * @test
     */
    public function it_gets_the_members()
    {
        self::assertEquals(1029210, $this->anime->getMembers());
    }

    /**
     * @test
     */
    public function it_gets_the_rated()
    {
        self::assertEquals($this->anime->getRated(), 'R');
    }

    /**
     * @test
     */
    public function it_gets_the_score()
    {
        self::assertEquals(8.56, $this->anime->getScore());
    }
}
