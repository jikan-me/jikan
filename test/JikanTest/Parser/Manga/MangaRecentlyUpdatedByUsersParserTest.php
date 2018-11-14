<?php

namespace JikanTest\Parser\Manga;

use PHPUnit\Framework\TestCase;

class MangaRecentlyUpdatedByUsersParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Manga\MangaRecentlyUpdatedByUsersParser
     */
    private $parser;

    public function setUp()
    {
        $request = new \Jikan\Request\Manga\MangaRecentlyUpdatedByUsersRequest(1);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = (new \Jikan\Parser\Manga\MangaRecentlyUpdatedByUsersParser($crawler))->getModel();
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaRecentlyUpdatedByUsersParser
     * @vcr MangaRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_recently_updated_by_users_count(): void
    {
        self::assertCount(75, $this->parser);
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaRecentlyUpdatedByUsersParser
     * @vcr MangaRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_username(): void
    {
        self::assertEquals(
            "Prajzy",
            $this->parser[6]->getUsername()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaRecentlyUpdatedByUsersParser
     * @vcr MangaRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_url(): void
    {
        self::assertEquals(
            "https://myanimelist.net/profile/Prajzy",
            $this->parser[6]->getUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaRecentlyUpdatedByUsersParser
     * @vcr MangaRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_image_url(): void
    {
        self::assertEquals(
            "https://myanimelist.cdn-dena.com/images/userimages/4554321.jpg",
            $this->parser[6]->getImageUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaRecentlyUpdatedByUsersParser
     * @vcr MangaRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_score(): void
    {
        self::assertEquals(
            10,
            $this->parser[6]->getScore()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaRecentlyUpdatedByUsersParser
     * @vcr MangaRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_status(): void
    {
        self::assertEquals(
            "Reading",
            $this->parser[6]->getStatus()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaRecentlyUpdatedByUsersParser
     * @vcr MangaRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_chapters_read(): void
    {
        self::assertEquals(
            60,
            $this->parser[6]->getChaptersRead()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaRecentlyUpdatedByUsersParser
     * @vcr MangaRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_chapters_total(): void
    {
        self::assertEquals(
            162,
            $this->parser[6]->getChaptersTotal()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaRecentlyUpdatedByUsersParser
     * @vcr MangaRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_volumes_read(): void
    {
        self::assertEquals(
            6,
            $this->parser[6]->getVolumesRead()
        );
    }


    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaRecentlyUpdatedByUsersParser
     * @vcr MangaRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_volumes_total(): void
    {
        self::assertEquals(
            18,
            $this->parser[6]->getVolumesTotal()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaRecentlyUpdatedByUsersParser
     * @vcr MangaRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_date(): void
    {
        self::assertInstanceOf(
            \DateTimeImmutable::class,
            $this->parser[0]->getDate()
        );
    }


}