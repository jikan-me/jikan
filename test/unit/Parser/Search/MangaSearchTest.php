<?php

namespace JikanTest\Parser\Search;

use Jikan\MyAnimeList\MalClient;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class MangaSearchTest
 */
class MangaSearchTest extends TestCase
{

    private $search;
    private $manga;

    public function setUp(): void
    {
        parent::setUp();

        $jikan = new MalClient($this->httpClient);
        $this->search = $jikan->getMangaSearch(
            new \Jikan\Request\Search\MangaSearchRequest('Fate')
        );
        $this->manga = $this->search->getResults()[0];
    }

    #[Test]
    public function it_gets_the_title()
    {
        self::assertEquals("Fate/Zero", $this->manga->getTitle());
    }

    #[Test]
    public function it_gets_the_image_url()
    {
        self::assertEquals("https://cdn.myanimelist.net/images/manga/3/196931.jpg?s=7da8d65371bc975c9ecda0d30a832984", $this->manga->getImages()->getJpg()->getImageUrl());
    }

    #[Test]
    public function it_gets_the_publishing()
    {
        self::assertEquals($this->manga->isPublishing(), false);
    }

    #[Test]
    public function it_gets_the_synopsis()
    {
        self::assertStringContainsString(
            "War of the Holy Grail—Pursuing the power of the \"Holy Grail\" which grants a miracle, this is a contest in which seven magi summon seven Heroic Spirits to compete for it.",
            $this->manga->getSynopsis()
        );
    }

    #[Test]
    public function it_gets_the_type()
    {
        self::assertEquals($this->manga->getType(), "Manga");
    }

    #[Test]
    public function it_gets_the_chapters()
    {
        self::assertEquals($this->manga->getChapters(), 79);
    }

    #[Test]
    public function it_gets_the_volumes()
    {
        self::assertEquals($this->manga->getVolumes(), 14);
    }

    #[Test]
    public function it_gets_the_start_date()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->manga->getStartDate());
    }

    #[Test]
    public function it_gets_the_end_date()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->manga->getEndDate());
    }

    #[Test]
    public function it_gets_the_members()
    {
        self::assertEquals($this->manga->getMembers(), 7680);
    }

    #[Test]
    public function it_gets_the_score()
    {
        self::assertEquals($this->manga->getScore(), 7.76);
    }
}
