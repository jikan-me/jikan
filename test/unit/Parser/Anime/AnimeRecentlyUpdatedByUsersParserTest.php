<?php

namespace JikanTest\Parser\Anime;

use JikanTest\ModelSnapshot;
use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

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
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->model = (new \Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser($crawler))->getModel();
    }

    #[Test]
    public function it_gets_recently_updated_by_users_count(): void
    {
        self::assertCount(75, $this->model->getResults());
    }

    #[Test]
    public function it_gets_username(): void
    {
        self::assertEquals(
            "radim5275",
            $this->model->getResults()[0]->getUser()->getUsername()
        );
    }

    #[Test]
    public function it_gets_url(): void
    {
        $url = $this->model->getResults()[0]->getUser()->getUrl();
        $this->assertIsString($url);
        $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL));
        $this->assertStringStartsWith('/profile/', parse_url($url)['path']);
    }

    #[Test]
    public function it_gets_image_url(): void
    {
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/.*~',
            $this->model->getResults()[0]->getUser()->getImages()->getJpg()->getImageUrl()
        );
    }

    #[Test]
    public function it_gets_score(): void
    {
        self::assertEquals(7, $this->model->getResults()[0]->getScore());
    }

    #[Test]
    public function it_gets_status(): void
    {
        self::assertEquals(
            "Watching",
            $this->model->getResults()[0]->getStatus()
        );
    }

    #[Test]
    public function it_gets_episodes_seen(): void
    {
        self::assertEquals(
            20,
            $this->model->getResults()[0]->getEpisodesSeen()
        );
    }

    #[Test]
    public function it_gets_episodes_total(): void
    {
        self::assertEquals(
            26,
            $this->model->getResults()[0]->getEpisodesTotal()
        );
    }

    #[Test]
    public function it_gets_date(): void
    {
        self::assertInstanceOf(
            \DateTimeImmutable::class,
            $this->model->getResults()[0]->getDate()
        );
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->model, true);
    }
}
