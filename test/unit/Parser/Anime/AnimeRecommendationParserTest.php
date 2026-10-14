<?php

namespace JikanTest\Parser\Anime;

use JikanTest\ModelSnapshot;
use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AnimeRecommendationParserTest extends TestCase
{
    /**
     * @var \Jikan\Model\Common\Recommendation[]
     */
    private $model;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Anime\AnimeRecommendationsRequest(21);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->model = (new \Jikan\Parser\Common\Recommendations($crawler))->getModel();
    }

    #[Test]
    public function it_get_recommendations_count(): void
    {
        self::assertCount(143, $this->model);
    }

    #[Test]
    public function it_gets_mal_id(): void
    {
        self::assertEquals(6702, $this->model[0]->getEntry()->getMalId());
    }

    #[Test]
    public function it_gets_url(): void
    {
        $url = $this->model[0]->getUrl();
        $this->assertIsString($url);
        $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL));
        $this->assertStringStartsWith('/recommendations/', parse_url($url)['path']);
    }

    #[Test]
    public function it_gets_image_url(): void
    {
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/.*~',
            $this->model[0]->getEntry()->getImages()->getJpg()->getImageUrl()
        );
    }

    #[Test]
    public function it_gets_recommendation_url(): void
    {
        self::assertEquals(
            "https://myanimelist.net/recommendations/anime/21-6702",
            $this->model[0]->getUrl()
        );
    }

    #[Test]
    public function it_gets_title(): void
    {
        self::assertEquals(
            "Fairy Tail",
            $this->model[0]->getEntry()->getTitle()
        );
    }

    #[Test]
    public function it_gets_recommendation_count(): void
    {
        self::assertCount(
            143,
            $this->model
        );
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->model);
    }
}
