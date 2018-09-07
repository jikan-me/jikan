<?php

namespace JikanTest\Parser\Search;

use PHPUnit\Framework\TestCase;
use Jikan\MyAnimeList\MalClient;

/**
 * Class MangaSearchTest
 */
class MangaSearchTest extends TestCase
{

    private $search;
    private $manga;

    public function setUp()
    {
        $jikan = new MalClient;
        $this->search = $jikan->getMangaSearch(
            new \Jikan\Request\Search\MangaSearchRequest('Fate')
        );
        $this->manga = $this->search->getResults()[0];
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_title()
    {
        self::assertEquals("Fate/Zero", $this->manga->getTitle());
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_image_url()
    {
        self::assertEquals("https://myanimelist.cdn-dena.com/images/manga/3/196931.jpg?s=7da8d65371bc975c9ecda0d30a832984", $this->manga->getImageUrl());
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_publishing()
    {
        self::assertEquals($this->manga->isPublishing(), false);
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_synopsis()
    {
        self::assertContains("War of the Holy Grail—Pursuing the power of the \"Holy Grail\" which grants a miracle, this is a contest in which seven magi summon seven Heroic Spirits to compete for it.", $this->manga->getSynopsis());
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_type()
    {
        self::assertEquals($this->manga->getType(), "Manga");
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_chapters()
    {
        self::assertEquals($this->manga->getChapters(), 79);
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_volumes()
    {
        self::assertEquals($this->manga->getVolumes(), 14);
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_start_date()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->manga->getStartDate());
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_end_date()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->manga->getEndDate());
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_members()
    {
        self::assertEquals($this->manga->getMembers(), 3659);
    }

    /**
     * @test
     * @vcr MangaSearchTest.yaml
     */
    public function it_gets_the_score()
    {
        self::assertEquals($this->manga->getScore(), 7.81);
    }
}
