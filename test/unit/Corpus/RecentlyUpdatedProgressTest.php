<?php

namespace JikanTest\Corpus;

use Jikan\Helper\Parser;
use Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersParser;
use Jikan\Parser\Manga\MangaRecentlyUpdatedByUsersParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\DomCrawler\Crawler;

/**
 * jikan#663: the progress cells of "recently updated by users" are printed as "5/12", "5/?" (total not known yet),
 * "-/12", a bare "-" (nothing yet, no slash) or nothing. A bare "-" made the total getters read a part that does
 * not exist; where warnings become exceptions (Laravel) the page's catch-all then returned no rows at all, and
 * "5/?" gave a total of 0. The pages anime-userupdates-21 and manga-userupdates-2 are recorded live and have all of
 * those shapes (manga 2: 39 rows with a bare "-" in the volumes column).
 */
class RecentlyUpdatedProgressTest extends TestCase
{
    /**
     * @return iterable<string, array{string, array{?int, ?int}}>
     */
    public static function cells(): iterable
    {
        yield 'read and total' => ['5/12', [5, 12]];
        yield 'total not known yet' => ['5/?', [5, null]];
        yield 'nothing read, total known' => ['-/12', [null, 12]];
        yield 'a bare dash' => ['-', [null, null]];
        yield 'empty' => ['', [null, null]];
        yield 'spaces around' => [" 5 / 12 \u{00A0}", [5, 12]];
        yield 'thousands separator' => ['1,044/1,100', [1044, 1100]];
        yield 'both unknown' => ['-/?', [null, null]];
        yield 'zero is a number' => ['0/0', [0, 0]];
        yield 'more than two parts is not a number' => ['5/12/7', [5, null]];
    }

    /**
     * @param array{?int, ?int} $expected
     */
    #[Test]
    #[DataProvider('cells')]
    public function a_progress_cell_reads_as_read_and_total(string $cell, array $expected): void
    {
        self::assertSame($expected, Parser::parseProgress($cell));
    }

    #[Test]
    public function the_recorded_manga_page_parses_with_warnings_turned_into_exceptions(): void
    {
        $rows = self::strictly(fn (): array => new MangaRecentlyUpdatedByUsersParser(Variants::crawler('manga-userupdates-2'))->getResults());

        self::assertCount(75, $rows);
        // a bare dash in the volumes column: both numbers unknown, and the row is still there
        $bare = array_filter($rows, static fn ($r): bool => $r->getVolumesRead() === null && $r->getVolumesTotal() === null);
        self::assertNotEmpty($bare);
        // "5/?": the read count is kept and the total is unknown, not 0
        $open = array_filter($rows, static fn ($r): bool => $r->getChaptersRead() !== null && $r->getChaptersTotal() === null);
        self::assertNotEmpty($open);
        foreach ($rows as $row) {
            self::assertNotSame(0, $row->getChaptersTotal(), 'an unknown total is null, not 0');
            self::assertNotSame(0, $row->getVolumesTotal());
        }
    }

    #[Test]
    public function the_recorded_anime_page_keeps_an_unknown_total_unknown(): void
    {
        $rows = self::strictly(fn (): array => new AnimeRecentlyUpdatedByUsersParser(Variants::crawler('anime-userupdates-21'))->getResults());

        self::assertCount(75, $rows);
        // One Piece is still airing: almost every row reads "N/?"
        $open = array_filter($rows, static fn ($r): bool => $r->getEpisodesSeen() !== null && $r->getEpisodesTotal() === null);
        self::assertGreaterThan(50, count($open));
        $bare = array_filter($rows, static fn ($r): bool => $r->getEpisodesSeen() === null && $r->getEpisodesTotal() === null);
        self::assertNotEmpty($bare, 'rows with a bare dash are in the page');
    }

    #[Test]
    public function a_page_without_the_table_has_no_rows(): void
    {
        $empty = new Crawler('<html><body><div id="content">Nobody has updated this yet.</div></body></html>');

        self::assertSame([], new MangaRecentlyUpdatedByUsersParser($empty)->getResults());
        self::assertSame([], new AnimeRecentlyUpdatedByUsersParser($empty)->getResults());
    }

    #[Test]
    public function a_row_that_cannot_be_read_is_an_error_not_an_empty_page(): void
    {
        $page = Variants::crawler('manga-userupdates-2');
        $date = $page->filterXPath('//table[@class="table-recently-updated"]/tbody/tr[3]/td[6]')->getNode(0);
        $date->textContent = 'not a date at all';

        $this->expectException(\Exception::class);
        new MangaRecentlyUpdatedByUsersParser($page)->getResults();
    }

    /**
     * @template T
     * @param callable(): T $run
     * @return T
     */
    private static function strictly(callable $run): mixed
    {
        set_error_handler(static function (int $no, string $message): never {
            throw new \ErrorException($message, 0, $no);
        });
        try {
            return $run();
        } finally {
            restore_error_handler();
        }
    }
}
