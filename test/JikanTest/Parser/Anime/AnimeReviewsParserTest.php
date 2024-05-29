<?php

namespace JikanTest\Parser\Anime;

use Jikan\Model\Anime\AnimeReview;
use Jikan\Parser\Anime\AnimeReviewsParser;
use Jikan\Request\Anime\AnimeReviewsRequest;
use JikanTest\TestCase;
use Symfony\Component\HttpClient\HttpClient;

class AnimeReviewsParserTest extends TestCase
{
    /**
     * @var \Jikan\Model\Anime\AnimeReviews
     */
    private $model;

    /**
     * @var \Jikan\Model\Anime\AnimeReview
     */
    private $review;

    public function setUp(): void
    {
        parent::setUp();

        $request = new AnimeReviewsRequest(1);
        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->model = (new AnimeReviewsParser($crawler))->getModel();
        $this->review = $this->model->getResults()[0];
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     */
    public function it_gets_reviews(): void
    {
        self::assertCount(20, $this->model->getResults());
        self::assertContainsOnlyInstancesOf(
            AnimeReview::class,
            $this->model->getResults()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     */
    public function it_gets_review_id(): void
    {
        self::assertEquals(7406, $this->review->getMalId());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     */
    public function it_gets_review_url(): void
    {
        self::assertEquals('https://myanimelist.net/reviews.php?id=7406', $this->review->getUrl());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     */
    public function it_gets_review_date(): void
    {
        self::assertEquals(1219556760, $this->review->getDate()->getTimestamp());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     */
    public function it_gets_reviewer_username(): void
    {
        self::assertEquals(
            'TheLlama',
            $this->review->getUser()->getUsername()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     */
    public function it_gets_reviewer_image_url(): void
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/userimages/11081.jpg?t=1681838400',
            $this->review->getUser()->getImages()->getJpg()->getImageUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     */
    public function it_gets_reviewer_url(): void
    {
        self::assertEquals(
            'https://myanimelist.net/profile/TheLlama',
            $this->review->getUser()->getUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     */
    public function it_gets_reviewer_episodes_watched(): void
    {
        self::assertEquals(
            null,
            $this->review->getEpisodesWatched()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     */
    public function it_gets_reviewer_reactions(): void
    {
        self::assertEquals(8, $this->review->getReactions()->getLoveIt());
        self::assertEquals(0, $this->review->getReactions()->getCreative());
        self::assertEquals(2, $this->review->getReactions()->getWellWritten());
        self::assertEquals(4, $this->review->getReactions()->getInformative());
        self::assertEquals(1, $this->review->getReactions()->getConfusing());
        self::assertEquals(1, $this->review->getReactions()->getFunny());
        self::assertEquals(2121, $this->review->getReactions()->getNice());
        self::assertEquals(2137, $this->review->getReactions()->getOverall());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     */
    public function it_gets_reviewer_review(): void
    {
        self::assertStringContainsString(
            'People who know me know that I\'m not a fan of episodic anime series unless they\'re either one season (12-14 episodes) long or a slice of life series',
            $this->review->getReview()
        );
        self::assertStringContainsString(
            'The characters are all really good and interesting fellows. Though they every now and then reminded me of characters from other shows, they preserved that originality which gave a feel that they were, if not completely, then at least a little bit more real than most characters out there.',
            $this->review->getReview()
        );
    }


}
