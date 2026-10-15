<?php

namespace JikanTest\Corpus;

use Jikan\Parser\Reviews\AnimeReviewParser;
use Jikan\Parser\Reviews\MangaReviewParser;
use Jikan\Parser\Reviews\ReviewerParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Review scores. MyAnimeList's 2022 review redesign dropped the per-category ratings (story, animation, art,
 * sound, character, enjoyment); a review shows one "Reviewer's Rating". getAnimeScores()/getMangaScores()
 * used to call a parser that no longer existed and always failed; they now return that overall rating, with
 * the category getters null.
 */
class ReviewScoresTest extends TestCase
{
    /**
     * @return iterable<string, array{string, bool}>
     */
    public static function pages(): iterable
    {
        yield 'anime reviews' => ['anime-reviews-1-p1', true];
        yield 'manga reviews' => ['manga-reviews-1-p1', false];
    }

    #[Test]
    #[DataProvider('pages')]
    public function the_helpful_count_is_the_total_of_the_reactions(string $id, bool $anime): void
    {
        // MyAnimeList replaced "helpful" votes with reactions; it used to be a hard-coded 0 (anime) or a failure (manga).
        $total = 0;
        foreach (Variants::crawler($id)->filter('div.review-element') as $node) {
            $review = new Crawler($node, 'https://myanimelist.net/');
            $parser = $anime ? new AnimeReviewParser($review) : new MangaReviewParser($review);
            $reactions = $parser->getReactions();
            $sum = $reactions->getNice() + $reactions->getLoveIt() + $reactions->getFunny() + $reactions->getConfusing()
                + $reactions->getInformative() + $reactions->getWellWritten() + $reactions->getCreative();

            self::assertSame($reactions->getOverall(), $parser->getHelpfulCount());
            self::assertSame($sum, $parser->getHelpfulCount(), 'the total MyAnimeList reports is the sum of the seven reactions');
            $total += $parser->getHelpfulCount();
        }
        self::assertGreaterThan(0, $total);
    }

    #[Test]
    #[DataProvider('pages')]
    public function the_scores_carry_the_reviewers_overall_rating(string $id, bool $anime): void
    {
        $reviews = Variants::crawler($id)->filter('div.review-element');
        self::assertGreaterThan(0, $reviews->count());

        foreach ($reviews as $node) {
            $review = new Crawler($node, 'https://myanimelist.net/');
            $parser = $anime ? new AnimeReviewParser($review) : new MangaReviewParser($review);
            $rating = $parser->getReviewerScore();
            self::assertGreaterThanOrEqual(1, $rating);
            self::assertLessThanOrEqual(10, $rating);

            $scores = $anime ? $parser->getAnimeScores() : $parser->getMangaScores();
            $viaReviewer = $anime ? new ReviewerParser($review)->getAnimeScores() : new ReviewerParser($review)->getMangaScores();
            self::assertSame($rating, $scores->getOverall());
            self::assertSame($rating, $viaReviewer->getOverall());
            self::assertNull($scores->getStory());
            self::assertNull($scores->getCharacter());
            self::assertNull($scores->getEnjoyment());
        }
    }
}
