<?php

namespace JikanTest\Parser\User\Reviews;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\User\Reviews\UserReviewsParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class UserReviewsParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\User\Reviews\UserReviewsParser
 * (getUserReviews) against a user's review history page (anime reviews).
 */
class UserReviewsParserTest extends TestCase
{
    /**
     * @var UserReviewsParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request(
            'GET',
            'https://myanimelist.net/profile/DatRandomDude/reviews?p=1'
        );

        $this->parser = new UserReviewsParser($crawler);
    }

    #[Test]
    public function it_parses_the_user_review_list(): void
    {
        $reviews = $this->parser->getReviews();
        self::assertCount(5, $reviews);
        self::assertContainsOnlyInstancesOf(
            \Jikan\Model\User\Reviews\UserAnimeReview::class,
            $reviews
        );
    }

    #[Test]
    public function it_parses_the_review_targets(): void
    {
        $reviews = $this->parser->getReviews();
        self::assertSame('Masamune-kun no Revenge', $reviews[0]->getEntry()->getTitle());
        self::assertSame(33487, $reviews[0]->getEntry()->getMalId());
        self::assertSame('Ranpo Kitan: Game of Laplace', $reviews[1]->getEntry()->getTitle());
    }

    #[Test]
    public function it_exposes_the_endpoint_model(): void
    {
        self::assertCount(5, $this->parser->getModel()->getResults());
    }
}