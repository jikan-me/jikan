<?php

namespace JikanTest\Parser\Manga;

use JikanTest\TestCase;

class MangaRecommendationParserTest extends TestCase
{
    /**
     * @var \Jikan\Model\Common\Recommendation[]
     */
    private $model;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Manga\MangaRecommendationsRequest(1);
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
        self::assertCount(46, $this->model);
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     */
    public function it_gets_mal_id(): void
    {
        self::assertEquals(21, $this->model[0]->getEntry()->getMalId());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     */
    public function it_gets_url(): void
    {
        self::assertEquals(
            "https://myanimelist.net/recommendations/manga/1-21",
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
            "https://cdn.myanimelist.net/images/manga/1/258245.jpg?s=dc85ade0b0e1083e92fd8c4509808626",
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
            "https://myanimelist.net/manga/21/Death_Note",
            $this->model[0]->getEntry()->getUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     */
    public function it_gets_title(): void
    {
        self::assertEquals(
            "Death Note",
            $this->model[0]->getEntry()->getTitle()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     */
    public function it_gets_votes_count(): void
    {
        self::assertEquals(
            12,
            $this->model[0]->getVotes()
        );
    }


}
