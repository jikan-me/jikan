<?php

namespace JikanTest\Parser\Anime;

use PHPUnit\Framework\TestCase;

class AnimeRecentlyUpdatedByUsersParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     */
    private $parser;

    public function setUp()
    {
        $request = new \Jikan\Request\Anime\AnimeRecentlyUpdatedByUsersRequest(1);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = (new \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser($crawler))->getModel();
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     * @vcr AnimeRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_recently_updated_by_users_count(): void
    {
        self::assertCount(75, $this->parser);
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     * @vcr AnimeRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_username(): void
    {
        self::assertEquals(
            "Zoidberg_",
            $this->parser[0]->getUsername()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     * @vcr AnimeRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_url(): void
    {
        self::assertEquals(
            "https://myanimelist.net/profile/Zoidberg_",
            $this->parser[0]->getUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     * @vcr AnimeRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_image_url(): void
    {
        self::assertEquals(
            "https://myanimelist.cdn-dena.com/images/userimages/6709119.jpg",
            $this->parser[0]->getImageUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     * @vcr AnimeRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_score(): void
    {
        self::assertEquals(
            null,
            $this->parser[0]->getScore()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     * @vcr AnimeRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_status(): void
    {
        self::assertEquals(
            "Watching",
            $this->parser[0]->getStatus()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     * @vcr AnimeRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_episodes_seen(): void
    {
        self::assertEquals(
            14,
            $this->parser[0]->getEpisodesSeen()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     * @vcr AnimeRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_episodes_total(): void
    {
        self::assertEquals(
            26,
            $this->parser[0]->getEpisodesTotal()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     * @vcr AnimeRecentlyUpdatedByUsersParserTest.yaml
     */
    public function it_gets_date(): void
    {
        self::assertInstanceOf(
            \DateTimeImmutable::class,
            $this->parser[0]->getDate()
        );
    }


}