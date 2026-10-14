<?php

namespace JikanTest\Corpus;

use Jikan\Parser\Article\ArticleListParser;
use Jikan\Parser\Genre\AnimeGenreParser;
use Jikan\Parser\Genre\MangaGenreParser;
use Jikan\Parser\News\NewsListParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * Synthetic variants: the last page of a paginated list.
 *
 * The recorded pages are all first pages, where there is always a next page. Each case takes one, moves the
 * "current" marker in its pagination bar to the last page link (through the DOM), and checks that
 * getHasNextPage() then says there is none.
 */
class LastPageTest extends TestCase
{
    /**
     * @return iterable<string, array{string, class-string}>
     */
    public static function lists(): iterable
    {
        yield 'anime genre' => ['anime-genre-1-p1', AnimeGenreParser::class];
        yield 'manga genre' => ['manga-genre-1-p1', MangaGenreParser::class];
        yield 'recent news' => ['recent-news-p1', NewsListParser::class];
        yield 'recent articles' => ['recent-articles-p1', ArticleListParser::class];
    }

    #[Test]
    #[DataProvider('lists')]
    public function the_last_page_has_no_next_page(string $id, string $parser): void
    {
        $page = Variants::crawler($id);
        self::assertTrue(new $parser($page)->getHasNextPage(), "$id is recorded on a page with a next page");

        $links = $page->filterXPath('//div[contains(@class, "pagination")]/a[contains(@class, "link")]');
        self::assertGreaterThan(1, $links->count(), "$id has a pagination bar");
        $last = null;
        foreach ($links as $link) {
            $link->setAttribute('class', trim(str_replace('current', '', $link->getAttribute('class'))));
            $last = $link;
        }
        $last->setAttribute('class', $last->getAttribute('class') . ' current');

        self::assertFalse(new $parser($page)->getHasNextPage());
    }
}
