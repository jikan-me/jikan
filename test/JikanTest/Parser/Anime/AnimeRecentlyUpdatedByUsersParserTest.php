<?php

namespace JikanTest\Parser\Anime;

use JikanTest\TestCase;

class AnimeRecentlyUpdatedByUsersParserTest extends TestCase
{
    /**
     * @var \Jikan\Model\Anime\AnimeUserUpdates
     */
    private $model;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Anime\AnimeRecentlyUpdatedByUsersRequest(1);
        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->model = (new \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser($crawler))->getModel();
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     */
    public function it_gets_recently_updated_by_users_count(): void
    {
        self::assertCount(75, $this->model->getResults());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     */
    public function it_gets_username(): void
    {
        self::assertEquals(
            "KyouTorii",
            $this->model->getResults()[0]->getUser()->getUsername()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     */
    public function it_gets_url(): void
    {
        self::assertEquals(
            "https://myanimelist.net/profile/KyouTorii",
            $this->model->getResults()[0]->getUser()->getUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     */
    public function it_gets_image_url(): void
    {
        self::assertEquals(
            "https://cdn.myanimelist.net/images/userimages/15361140.jpg?t=1664641200",
            $this->model->getResults()[0]->getUser()->getImages()->getJpg()->getImageUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     */
    public function it_gets_score(): void
    {
        self::assertNull($this->model->getResults()[0]->getScore());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     */
    public function it_gets_status(): void
    {
        self::assertEquals(
            "Plan to Watch",
            $this->model->getResults()[0]->getStatus()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     */
    public function it_gets_episodes_seen(): void
    {
        self::assertEquals(
            null,
            $this->model->getResults()[0]->getEpisodesSeen()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     */
    public function it_gets_episodes_total(): void
    {
        self::assertEquals(
            null,
            $this->model->getResults()[0]->getEpisodesTotal()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser
     */
    public function it_gets_date(): void
    {
        self::assertInstanceOf(
            \DateTimeImmutable::class,
            $this->model->getResults()[0]->getDate()
        );
    }


}
