<?php

namespace JikanTest\Parser\Search;

use PHPUnit\Framework\TestCase;
use Jikan\Jikan;

/**
 * Class MangaSearchTest
 */
class MangaSearchTest extends TestCase
{

    private $search;
    private $anime;

    public function setUp()
    {
        $jikan = new Jikan;
        $this->search = $jikan->MangaSearch(
            new \Jikan\Request\Search\MangaSearchRequest('Fate')
        );
        $this->anime = $this->search->getResults()[0];
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_title()
    {
        self::assertEquals("Fate/Zero", $this->anime->getTitle());
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_image_url()
    {
        self::assertEquals("https://myanimelist.cdn-dena.com/r/50x70/images/manga/3/196931.jpg?s=7da8d65371bc975c9ecda0d30a832984", $this->anime->getImageUrl());
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_publishing()
    {
        self::assertEquals($this->anime->isPublishing(), false);
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_synopsis()
    {
        self::assertContains("War of the Holy Grail—Pursuing the power of the \"Holy Grail\" which grants a miracle, this is a contest in which seven magi summon seven Heroic Spirits to compete for it.", $this->anime->getSynopsis());
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_type()
    {
        self::assertEquals($this->anime->getType(), "Manga");
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_chapters()
    {
        self::assertEquals($this->anime->getChapters(), 79);
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_volumes()
    {
        self::assertEquals($this->anime->getVolumes(), 14);
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_start_date()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->anime->getStartDate());
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_end_date()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->anime->getEndDate());
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_members()
    {
        self::assertEquals($this->anime->getMembers(), 3659);
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_score()
    {
        self::assertEquals($this->anime->getScore(), 7.81);
    }
}
