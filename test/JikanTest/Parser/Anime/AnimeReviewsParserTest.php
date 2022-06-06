<?php

namespace JikanTest\Parser\Anime;

use Jikan\Model\Anime\AnimeReview;
use Jikan\Parser\Anime\AnimeReviewsParser;
use Jikan\Request\Anime\AnimeReviewsRequest;
use JikanTest\TestCase;

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
    public function it_gets_review_votes_count(): void
    {
        self::assertEquals(2034, $this->review->getVotes());
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
            'https://cdn.myanimelist.net/images/userimages/11081.jpg?t=1654492800',
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
            26,
            $this->review->getEpisodesWatched()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     */
    public function it_gets_reviewer_scores(): void
    {
        self::assertEquals(10, $this->review->getScores()->getOverall());
        self::assertEquals(10, $this->review->getScores()->getStory());
        self::assertEquals(9, $this->review->getScores()->getAnimation());
        self::assertEquals(10, $this->review->getScores()->getSound());
        self::assertEquals(10, $this->review->getScores()->getCharacter());
        self::assertEquals(9, $this->review->getScores()->getEnjoyment());
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