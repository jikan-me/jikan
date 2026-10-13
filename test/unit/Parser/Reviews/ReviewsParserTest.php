<?php

namespace JikanTest\Parser\Reviews;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Reviews\ReviewsParser;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class ReviewsParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Reviews\ReviewsParser (getReviews)
 * against the default anime reviews listing (most voted, spoilers + preliminary
 * included).
 */
class ReviewsParserTest extends TestCase
{
    /**
     * @var ReviewsParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Reviews\ReviewsRequest();
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new ReviewsParser($crawler);
    }

    #[Test]
    public function it_parses_the_review_list(): void
    {
        $reviews = $this->parser->getReviews();
        self::assertCount(50, $reviews);
        self::assertContainsOnlyInstancesOf(
            \Jikan\Model\Reviews\FullAnimeReview::class,
            $reviews
        );
    }

    #[Test]
    public function it_parses_the_review_targets(): void
    {
        $reviews = $this->parser->getReviews();
        self::assertSame('Nige Jouzu no Wakagimi 2nd Season', $reviews[0]->getEntry()->getTitle());
        self::assertSame(60059, $reviews[0]->getEntry()->getMalId());
        self::assertSame('NHK ni Youkoso!', $reviews[1]->getEntry()->getTitle());
    }

    #[Test]
    public function it_builds_the_same_model_when_parsed_twice(): void
    {
        self::assertEquals($this->parser->getModel(), $this->parser->getModel());
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->parser->getModel());
    }
}
