<?php

namespace JikanTest\Parser\Anime;

use Jikan\Model\Anime\AnimeReview;
use Jikan\Parser\Anime\AnimeReviewsParser;
use Jikan\Request\Anime\AnimeReviewsRequest;
use PHPUnit\Framework\TestCase;

class AnimeReviewsParserTest extends TestCase
{
    /**
     * @var AnimeReview[]
     */
    private $parser;

    /**
     * @var AnimeReview
     */
    private $review;

    public function setUp()
    {
        $request = new AnimeReviewsRequest(1);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = (new AnimeReviewsParser($crawler))->getModel();
        $this->review = $this->parser[0];
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     * @vcr AnimeReviewsParserTest.yaml
     */
    public function it_gets_reviews(): void
    {
        self::assertCount(20, $this->parser);
        self::assertContainsOnlyInstancesOf(
            AnimeReview::class,
            $this->parser
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     * @vcr AnimeReviewsParserTest.yaml
     */
    public function it_gets_review_id(): void
    {
        self::assertEquals(7406, $this->review->getMalId());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     * @vcr AnimeReviewsParserTest.yaml
     */
    public function it_gets_review_url(): void
    {
        self::assertEquals('https://myanimelist.net/reviews.php?id=7406', $this->review->getUrl());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     * @vcr AnimeReviewsParserTest.yaml
     */
    public function it_gets_review_helpful_count(): void
    {
        self::assertEquals(1488, $this->review->getHelpfulCount());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     * @vcr AnimeReviewsParserTest.yaml
     */
    public function it_gets_review_date(): void
    {
        self::assertEquals(1219556760, $this->review->getDate()->getTimestamp());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     * @vcr AnimeReviewsParserTest.yaml
     */
    public function it_gets_reviewer_username(): void
    {
        self::assertEquals(
            'TheLlama',
            $this->review->getReviewer()->getUsername()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     * @vcr AnimeReviewsParserTest.yaml
     */
    public function it_gets_reviewer_image_url(): void
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/userimages/11081.jpg',
            $this->review->getReviewer()->getImageUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     * @vcr AnimeReviewsParserTest.yaml
     */
    public function it_gets_reviewer_url(): void
    {
        self::assertEquals(
            'https://myanimelist.net/profile/TheLlama',
            $this->review->getReviewer()->getUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     * @vcr AnimeReviewsParserTest.yaml
     */
    public function it_gets_reviewer_episodes_seen(): void
    {
        self::assertEquals(
            26,
            $this->review->getReviewer()->getEpisodesSeen()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     * @vcr AnimeReviewsParserTest.yaml
     */
    public function it_gets_reviewer_scores(): void
    {
        self::assertEquals(10, $this->review->getReviewer()->getScores()->getOverall());
        self::assertEquals(10, $this->review->getReviewer()->getScores()->getStory());
        self::assertEquals(9, $this->review->getReviewer()->getScores()->getAnimation());
        self::assertEquals(10, $this->review->getReviewer()->getScores()->getSound());
        self::assertEquals(10, $this->review->getReviewer()->getScores()->getCharacter());
        self::assertEquals(9, $this->review->getReviewer()->getScores()->getEnjoyment());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeReviewsParser
     * @vcr AnimeReviewsParserTest.yaml
     */
    public function it_gets_reviewer_review(): void
    {
        self::assertContains(
            'People who know me know that I\'m not a fan of episodic anime series unless they\'re either one season (12-14 episodes) long or a slice of life series',
            $this->review->getContent()
        );
        self::assertContains(
            'The characters are all really good and interesting fellows. Though they every now and then reminded me of characters from other shows, they preserved that originality which gave a feel that they were, if not completely, then at least a little bit more real than most characters out there.',
            $this->review->getContent()
        );
    }


}