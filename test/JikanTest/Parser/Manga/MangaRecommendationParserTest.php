<?php

namespace JikanTest\Parser\Manga;

use PHPUnit\Framework\TestCase;

class MangaRecommendationParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Common\Recommendations
     */
    private $parser;

    public function setUp()
    {
        $request = new \Jikan\Request\Manga\MangaRecommendationsRequest(1);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = (new \Jikan\Parser\Common\Recommendations($crawler))->getModel();
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     * @vcr MangaRecommendationParserTest.yaml
     */
    public function it_get_recommendations_count(): void
    {
        self::assertCount(36, $this->parser);
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     * @vcr MangaRecommendationParserTest.yaml
     */
    public function it_gets_mal_id(): void
    {
        self::assertEquals(21, $this->parser[0]->getMalId());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     * @vcr MangaRecommendationParserTest.yaml
     */
    public function it_gets_url(): void
    {
        self::assertEquals(
            "https://myanimelist.net/manga/21/Death_Note",
            $this->parser[0]->getUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     * @vcr MangaRecommendationParserTest.yaml
     */
    public function it_gets_image_url(): void
    {
        self::assertEquals(
            "https://myanimelist.cdn-dena.com/images/manga/2/54453.jpg?s=64e676c2d2ea8370b400a0503db2bc46",
            $this->parser[0]->getImageUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     * @vcr MangaRecommendationParserTest.yaml
     */
    public function it_gets_recommendation_url(): void
    {
        self::assertEquals(
            "https://myanimelist.net/recommendations/manga/1-21",
            $this->parser[0]->getRecommendationUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     * @vcr MangaRecommendationParserTest.yaml
     */
    public function it_gets_title(): void
    {
        self::assertEquals(
            "Death Note",
            $this->parser[0]->getTitle()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     * @vcr MangaRecommendationParserTest.yaml
     */
    public function it_gets_recommendation_count(): void
    {
        self::assertEquals(
            10,
            $this->parser[0]->getRecommendationCount()
        );
    }


}