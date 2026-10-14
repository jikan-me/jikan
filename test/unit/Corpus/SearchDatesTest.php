<?php

namespace JikanTest\Corpus;

use Jikan\Parser\Search\AnimeSearchListItemParser;
use Jikan\Parser\Search\MangaSearchListItemParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Synthetic variants of the start and end date columns in anime and manga search results.
 *
 * MyAnimeList shows "-" for a date it does not know (an announced title, or one still airing). The test clock is
 * frozen at 2026-10-12 12:00 UTC (see TestCase), so "04-01-20" is in the past and "04-01-30" in the future.
 */
class SearchDatesTest extends TestCase
{
    private const ROW = '//div[contains(@class, "js-categories-seasonal")]/table/tbody/tr[2]';

    /**
     * @return iterable<string, array{string, class-string, int, int}>
     */
    public static function searches(): iterable
    {
        // [variant, item parser, start date column, end date column]
        yield 'anime' => ['anime-search-fate', AnimeSearchListItemParser::class, 6, 7];
        yield 'manga' => ['manga-search-monster', MangaSearchListItemParser::class, 7, 8];
    }

    #[Test]
    #[DataProvider('searches')]
    public function an_unknown_date_is_null(string $id, string $parser, int $start, int $end): void
    {
        $row = self::row($id, [$start => '-', $end => '-']);
        $item = new $parser($row);

        self::assertNull($item->getStartDateString());
        self::assertNull($item->getStartDate());
        self::assertNull($item->getEndDateString());
        self::assertNull($item->getEndDate());
    }

    #[Test]
    #[DataProvider('searches')]
    public function a_known_date_is_read_as_month_day_year(string $id, string $parser, int $start, int $end): void
    {
        $item = new $parser(self::row($id, [$start => '04-01-20', $end => '09-30-21']));

        self::assertSame('04-01-20', $item->getStartDateString());
        self::assertSame('2020-04-01', $item->getStartDate()->format('Y-m-d'));
        self::assertSame('09-30-21', $item->getEndDateString());
        self::assertSame('2021-09-30', $item->getEndDate()->format('Y-m-d'));
    }

    /**
     * @return iterable<string, array{string, string, bool}>
     */
    public static function airing(): iterable
    {
        yield 'start unknown' => ['-', '-', false];
        yield 'started, no end yet' => ['04-01-20', '-', true];
        yield 'not started yet' => ['04-01-30', '09-30-30', false];
        yield 'already ended' => ['04-01-20', '09-30-21', false];
        yield 'started, ends later' => ['04-01-20', '09-30-30', true];
    }

    #[Test]
    #[DataProvider('airing')]
    public function an_anime_is_airing_between_its_start_and_end(string $startDate, string $endDate, bool $airing): void
    {
        // 4 is the episodes column; a one-episode anime is never "airing".
        $item = new AnimeSearchListItemParser(self::row('anime-search-fate', [4 => '12', 6 => $startDate, 7 => $endDate]));

        self::assertSame($airing, $item->isAiring());
    }

    #[Test]
    public function a_single_episode_anime_is_never_airing(): void
    {
        $item = new AnimeSearchListItemParser(self::row('anime-search-fate', [4 => '1', 6 => '04-01-20', 7 => '-']));

        self::assertFalse($item->isAiring());
    }

    /**
     * The first result row of a recorded search page, with the given columns (1-based) set to the given text.
     *
     * @param array<int, string> $cells
     */
    private static function row(string $id, array $cells): Crawler
    {
        $row = Variants::crawler($id)->filterXPath(self::ROW);
        self::assertSame(1, $row->count(), "$id has a first result row");
        foreach ($cells as $column => $text) {
            $cell = $row->filterXPath("//td[$column]")->getNode(0);
            self::assertNotNull($cell, "$id has a column $column");
            $cell->textContent = $text;
        }

        return $row;
    }
}
