<?php

namespace JikanTest\Parser\Anime;

use JikanTest\TestCase;

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
        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->model = (new \Jikan\Parser\Common\Recommendations($crawler))->getModel();
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     */
    public function it_get_recommendations_count(): void
    {
        self::assertCount(131, $this->model);
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     */
    public function it_gets_mal_id(): void
    {
        self::assertEquals(6702, $this->model[0]->getEntry()->getMalId());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     */
    public function it_gets_url(): void
    {
        self::assertEquals(
            "https://myanimelist.net/recommendations/anime/21-6702",
            $this->model[0]->getUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     */
    public function it_gets_image_url(): void
    {
        self::assertEquals(
            "https://cdn.myanimelist.net/images/anime/5/18179.jpg?s=24a281654f63558f3ef001950a9e6539",
            $this->model[0]->getEntry()->getImages()->getJpg()->getImageUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     */
    public function it_gets_recommendation_url(): void
    {
        self::assertEquals(
            "https://myanimelist.net/recommendations/anime/21-6702",
            $this->model[0]->getUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     */
    public function it_gets_title(): void
    {
        self::assertEquals(
            "Fairy Tail",
            $this->model[0]->getEntry()->getTitle()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     */
    public function it_gets_recommendation_count(): void
    {
        self::assertCount(
            131,
            $this->model
        );
    }


}
