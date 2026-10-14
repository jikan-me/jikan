<?php

namespace JikanTest\Corpus;

use Jikan\Parser\Anime\AnimeStatsParser;
use Jikan\Parser\Manga\MangaStatsParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Synthetic variants of the anime/manga stats page's "Score Stats" table.
 *
 * The recorded pages are popular titles with votes for every score from 1 to 10. A less popular title can have
 * scores nobody voted for (MyAnimeList leaves those rows out) or no votes at all (MyAnimeList prints a sentence
 * instead of the table). Each case rewrites the recorded table through the DOM.
 */
class StatsScoresTest extends TestCase
{
    /**
     * @return iterable<string, array{string, class-string}>
     */
    public static function pages(): iterable
    {
        yield 'anime' => ['anime-stats-1', AnimeStatsParser::class];
        yield 'manga' => ['manga-stats-1', MangaStatsParser::class];
    }

    #[Test]
    #[DataProvider('pages')]
    public function scores_nobody_voted_for_are_filled_in_with_zero_votes(string $id, string $parser): void
    {
        $page = Variants::crawler($id);
        $rows = self::rows($page);
        self::assertCount(10, $rows, "$id has a row for every score");

        // Drop the rows for 1, 2 and 10, as MyAnimeList does when nobody gave them.
        foreach ($rows as $row) {
            if (in_array((int) trim($row->firstElementChild->textContent), [1, 2, 10], true)) {
                $row->parentNode->removeChild($row);
            }
        }

        $scores = new $parser($page)->getScores();
        self::assertSame(range(1, 10), array_keys($scores));
        foreach ([1, 2, 10] as $missing) {
            self::assertSame($missing, $scores[$missing]->getScore());
            self::assertSame(0, $scores[$missing]->getVotes());
            self::assertEquals(0.0, $scores[$missing]->getPercentage());
        }
        self::assertGreaterThan(0, $scores[5]->getVotes());
    }

    #[Test]
    #[DataProvider('pages')]
    public function a_title_without_votes_has_no_scores(string $id, string $parser): void
    {
        // As served for an unaired title (anime/59895, 2026-10-14):
        // <h2 id="score_stats">Score Stats</h2>No scores have been recorded for this anime.<br><br>...
        $page = Variants::crawler($id);
        $heading = $page->filterXPath('//h2[text()="Score Stats"]')->getNode(0);
        $table = $page->filterXPath('//h2[text()="Score Stats"]/following-sibling::table[1]')->getNode(0);
        self::assertNotNull($table, "$id has a Score Stats table");
        $table->parentNode->removeChild($table);
        $heading->parentNode->insertBefore(
            $heading->ownerDocument->createTextNode('No scores have been recorded for this anime.'),
            $heading->nextSibling
        );

        self::assertSame([], new $parser($page)->getScores());
    }

    /**
     * @return list<\DOMElement>
     */
    private static function rows(Crawler $page): array
    {
        return iterator_to_array(
            $page->filterXPath('//h2[text()="Score Stats"]/following-sibling::table[1]/tbody/tr'),
            false
        );
    }
}
