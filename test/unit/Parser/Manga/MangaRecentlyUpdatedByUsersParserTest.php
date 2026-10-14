<?php

namespace JikanTest\Parser\Manga;

use JikanTest\ModelSnapshot;
use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MangaRecentlyUpdatedByUsersParserTest extends TestCase
{
    /**
     * @var \Jikan\Model\Manga\MangaUserUpdates
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Manga\MangaRecentlyUpdatedByUsersRequest(1);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = (new \Jikan\Parser\Manga\MangaRecentlyUpdatedByUsersParser($crawler))->getModel();
    }

    #[Test]
    public function it_gets_recently_updated_by_users_count(): void
    {
        self::assertCount(75, $this->parser->getResults());
    }

    #[Test]
    public function it_gets_username(): void
    {
        self::assertEquals(
            "lampfan3005",
            $this->parser->getResults()[6]->getUser()->getUsername()
        );

    }

    #[Test]
    public function it_gets_url(): void
    {
        $url = $this->parser->getResults()[6]->getUser()->getUrl();
        $this->assertIsString($url);
        $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL));
        $this->assertStringStartsWith('/profile/', parse_url($url)['path']);
    }

    #[Test]
    public function it_gets_image_url(): void
    {
        // The default kaomoji avatar is a placeholder — a user with no custom
        // picture surfaces null, not MAL's default avatar (jikan#488).
        self::assertNull(
            $this->parser->getResults()[6]->getUser()->getImages()->getJpg()->getImageUrl()
        );
    }

    #[Test]
    public function it_gets_score(): void
    {
        self::assertEquals(10, $this->parser->getResults()[6]->getScore());
    }

    #[Test]
    public function it_gets_status(): void
    {
        self::assertEquals(
            "Completed",
            $this->parser->getResults()[6]->getStatus()
        );
    }

    #[Test]
    public function it_gets_chapters_read(): void
    {
        self::assertEquals(
            162,
            $this->parser->getResults()[6]->getChaptersRead()
        );
    }

    #[Test]
    public function it_gets_chapters_total(): void
    {
        self::assertEquals(
            162,
            $this->parser->getResults()[6]->getChaptersTotal()
        );
    }

    #[Test]
    public function it_gets_volumes_read(): void
    {
        self::assertEquals(18, $this->parser->getResults()[6]->getVolumesRead());
    }


    #[Test]
    public function it_gets_volumes_total(): void
    {
        self::assertEquals(
            18,
            $this->parser->getResults()[6]->getVolumesTotal()
        );
    }

    #[Test]
    public function it_gets_date(): void
    {
        self::assertInstanceOf(
            \DateTimeImmutable::class,
            $this->parser->getResults()[0]->getDate()
        );
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->parser);
    }
}
