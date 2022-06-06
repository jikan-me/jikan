<?php

namespace JikanTest\Parser\Manga;

use Jikan\Model\Manga\MangaReview;
use Jikan\Parser\Manga\MangaReviewsParser;
use Jikan\Request\Manga\MangaReviewsRequest;
use JikanTest\TestCase;

class MangaReviewsParserTest extends TestCase
{
    /**
     * @var \Jikan\Model\Manga\MangaReviews
     */
    private $model;

    /**
     * @var MangaReview
     */
    private $review;

    public function setUp(): void
    {
        parent::setUp();

        $request = new MangaReviewsRequest(1);
        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->model = (new MangaReviewsParser($crawler))->getModel();
        $this->review = $this->model->getResults()[0];
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaReviewsParser
     */
    public function it_gets_reviews(): void
    {
        self::assertCount(20, $this->model->getResults());
        self::assertContainsOnlyInstancesOf(
            MangaReview::class,
            $this->model->getResults()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaReviewsParser
     */
    public function it_gets_review_id(): void
    {
        self::assertEquals(11794, $this->review->getMalId());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaReviewsParser
     */
    public function it_gets_review_url(): void
    {
        self::assertEquals('https://myanimelist.net/reviews.php?id=11794', $this->review->getUrl());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaReviewsParser
     */
    public function it_gets_review_votes_count(): void
    {
        self::assertEquals(411, $this->review->getVotes());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaReviewsParser
     */
    public function it_gets_review_date(): void
    {
        self::assertEquals(1232768580, $this->review->getDate()->getTimestamp());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaReviewsParser
     */
    public function it_gets_reviewer_username(): void
    {
        self::assertEquals(
            'BorisSoad',
            $this->review->getUser()->getUsername()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaReviewsParser
     */
    public function it_gets_reviewer_image_url(): void
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/userimages/132886.jpg?t=1368353400',
            $this->review->getUser()->getImages()->getJpg()->getImageUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaReviewsParser
     */
    public function it_gets_reviewer_url(): void
    {
        self::assertEquals(
            'https://myanimelist.net/profile/BorisSoad',
            $this->review->getUser()->getUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaReviewsParser
     */
    public function it_gets_reviewer_chapters_read(): void
    {
        self::assertEquals(
            162,
            $this->review->getChaptersRead()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaReviewsParser
     */
    public function it_gets_reviewer_scores(): void
    {
        self::assertEquals(10, $this->review->getScores()->getOverall());
        self::assertEquals(10, $this->review->getScores()->getStory());
        self::assertEquals(9, $this->review->getScores()->getArt());
        self::assertEquals(10, $this->review->getScores()->getCharacter());
        self::assertEquals(10, $this->review->getScores()->getEnjoyment());
    }

    /**
     * @test
     * @covers \Jikan\Parser\Manga\MangaReviewsParser
     */
    public function it_gets_reviewer_review(): void
    {
        self::assertStringContainsString(
            'Monster isn\'t a tradional manga. It isn\'t about fighting. I even dare to say it is a \'Love it or hate it\'-manga. If you are the type of Naruto and Bleach and looking for that kind of manga, this isn\'t the manga for you. If you are looking for an intense, well-written manga, I would recommend this certainly for you.',
            $this->review->getReview()
        );
        self::assertStringContainsString(
            'My conclusion: This manga is perfect for everyone who loves thrillers and tension! The 10 it has gotten from me, well, it just deserves it!',
            $this->review->getReview()
        );
    }


}