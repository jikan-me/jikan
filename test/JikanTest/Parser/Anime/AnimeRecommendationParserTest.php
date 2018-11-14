<?php

namespace JikanTest\Parser\Anime;

use PHPUnit\Framework\TestCase;

class AnimeRecommendationParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Common\Recommendations
     */
    private $parser;

    public function setUp()
    {
        $request = new \Jikan\Request\Anime\AnimeRecommendationsRequest(21);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = (new \Jikan\Parser\Common\Recommendations($crawler))->getModel();
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     * @vcr AnimeRecommendationParserTest.yaml
     */
    public function it_get_recommendations_count(): void
    {
        self::assertCount(98, $this->parser);
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     * @vcr AnimeRecommendationParserTest.yaml
     */
    public function it_gets_mal_id(): void
    {
        self::assertEquals(6702, $this->parser[0]->getMalId());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     * @vcr AnimeRecommendationParserTest.yaml
     */
    public function it_gets_url(): void
    {
        self::assertEquals(
            "https://myanimelist.net/anime/6702/Fairy_Tail",
            $this->parser[0]->getUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     * @vcr AnimeRecommendationParserTest.yaml
     */
    public function it_gets_image_url(): void
    {
        self::assertEquals(
            "https://myanimelist.cdn-dena.com/images/anime/5/18179.jpg?s=24a281654f63558f3ef001950a9e6539",
            $this->parser[0]->getImageUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     * @vcr AnimeRecommendationParserTest.yaml
     */
    public function it_gets_recommendation_url(): void
    {
        self::assertEquals(
            "https://myanimelist.net/recommendations/anime/21-6702",
            $this->parser[0]->getRecommendationUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     * @vcr AnimeRecommendationParserTest.yaml
     */
    public function it_gets_title(): void
    {
        self::assertEquals(
            "Fairy Tail",
            $this->parser[0]->getTitle()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Common\Recommendations
     * @vcr AnimeRecommendationParserTest.yaml
     */
    public function it_gets_recommendation_count(): void
    {
        self::assertEquals(
            83,
            $this->parser[0]->getRecommendationCount()
        );
    }


}