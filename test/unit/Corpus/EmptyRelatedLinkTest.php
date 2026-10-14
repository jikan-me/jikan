<?php

namespace JikanTest\Corpus;

use Jikan\Helper\JString;
use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Anime\AnimeParser;
use Jikan\Parser\Article\ArticleParser;
use Jikan\Parser\Manga\MangaParser;
use Jikan\Parser\News\NewsParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Synthetic variants: MyAnimeList sometimes lists a related entry as an empty link (<a href="..."></a>, for
 * example when the entry was removed). An empty link is not a related entry.
 *
 * MyAnimeList renders related entries in different layouts depending on the page: anime and manga pages have
 * tiles (one title link each) and/or a table (any number of links per row), news and articles have their own
 * tables. So every recorded page of those kinds is tested, in every layout it has, on every row.
 */
class EmptyRelatedLinkTest extends TestCase
{
    /** Per corpus method: the parser, its related-entries getter, and the layouts its pages can have. */
    private const PAGES = [
        'getAnime' => [AnimeParser::class, 'getRelated', ['tiles', 'table']],
        'getManga' => [MangaParser::class, 'getMangaRelated', ['tiles', 'table']],
        'getNews' => [NewsParser::class, 'getRelatedEntries', ['news']],
        'getArticle' => [ArticleParser::class, 'getRelatedEntries', ['article']],
    ];

    /** Per layout: the rows, the element holding a row's links (relative to the row), and the row's relation name. */
    private const LAYOUTS = [
        'tiles' => [
            '//div[contains(@class, "related-entries")]/div[contains(@class, "entries-tile")]/div[contains(@class, "entry")]',
            './/div[@class="content"]/div[@class="title"]',
            './/div[@class="content"]/div[@class="relation"]',
        ],
        'table' => ['//table[contains(@class, "entries-table")]/tbody/tr', 'td[2]', 'td[1]'],
        'news' => ['//table[contains(@class, "news-related-database")]/tbody/tr', 'td[2]', 'td[1]'],
        'article' => ['//*[contains(text(), "Related Database Entries")]/following-sibling::table/tbody/tr', 'td[2]', 'td[1]'],
    ];

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function layouts(): iterable
    {
        foreach (Variants::all() as $variant) {
            foreach (self::PAGES[$variant['method']][2] ?? [] as $layout) {
                if (self::rows(Variants::crawler($variant['id']), $layout) !== []) {
                    yield "{$variant['id']} $layout" => [$variant['id'], $layout];
                }
            }
        }
    }

    #[Test]
    #[DataProvider('layouts')]
    public function empty_links_are_not_related_entries(string $id, string $layout): void
    {
        $page = Variants::crawler($id);
        $before = self::related($id, $page);

        // Whitespace and a non-breaking space only: both render as nothing.
        foreach (self::rows($page, $layout) as $row) {
            self::links($row, $layout)->appendChild(self::emptyLink($row, "\n    "));
            self::links($row, $layout)->appendChild(self::emptyLink($row, "\u{00A0}"));
        }

        self::assertSame($before, self::related($id, $page));
    }

    #[Test]
    #[DataProvider('layouts')]
    public function a_row_whose_only_link_is_empty_adds_no_entries(string $id, string $layout): void
    {
        $before = self::related($id, Variants::crawler($id));
        $count = count(self::rows(Variants::crawler($id), $layout));

        for ($i = 0; $i < $count; $i++) {
            $page = Variants::crawler($id);
            $row = self::rows($page, $layout)[$i];
            $relation = self::relation($row, $layout);
            $cell = self::links($row, $layout);
            $removed = [];
            foreach (new \DOMXPath($cell->ownerDocument)->query('.//a', $cell) as $a) {
                $removed[] = self::absolute($a->getAttribute('href'));
            }
            while ($cell->firstChild) {
                $cell->removeChild($cell->firstChild);
            }
            $cell->appendChild(self::emptyLink($row, ''));

            // The relation stays (with whatever its other rows hold); only this row's entries are gone.
            $after = self::related($id, $page);
            self::assertArrayHasKey($relation, $after, "$id $layout row $i ($relation)");
            self::assertSame(
                array_values(array_diff($before[$relation], $removed)),
                $after[$relation],
                "$id $layout row $i ($relation)"
            );
        }
    }

    /**
     * @return array<string, list<string>> related entry URLs by relation
     */
    private static function related(string $id, Crawler $page): array
    {
        foreach (Variants::all() as $variant) {
            if ($variant['id'] === $id) {
                [$parser, $getter] = self::PAGES[$variant['method']];

                return array_map(
                    static fn (array $links): array => array_map(static fn (MalUrl $u): string => $u->getUrl(), $links),
                    new $parser($page)->$getter()
                );
            }
        }
        throw new \RuntimeException("No corpus variant $id");
    }

    /**
     * Rows that hold at least one link (a tile without a title link is skipped by the parser too).
     *
     * @return list<\DOMElement>
     */
    private static function rows(Crawler $page, string $layout): array
    {
        $xpath = new \DOMXPath($page->getNode(0)->ownerDocument);
        $rows = [];
        foreach ($xpath->query(self::LAYOUTS[$layout][0]) as $row) {
            $cell = $xpath->query(self::LAYOUTS[$layout][1], $row)->item(0);
            if ($cell !== null && $xpath->query('.//a', $cell)->length) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    private static function links(\DOMElement $row, string $layout): \DOMElement
    {
        return new \DOMXPath($row->ownerDocument)->query(self::LAYOUTS[$layout][1], $row)->item(0);
    }

    /** The relation name as the parsers derive it: "Sequel (TV)" and "Sequel:" both become "Sequel". */
    private static function relation(\DOMElement $row, string $layout): string
    {
        $text = new \DOMXPath($row->ownerDocument)->query(self::LAYOUTS[$layout][2], $row)->item(0)->textContent;

        return JString::cleanse(str_replace(':', '', preg_replace('~\s\(.*\)~', '', $text)));
    }

    private static function emptyLink(\DOMElement $row, string $text): \DOMElement
    {
        $link = $row->ownerDocument->createElement('a', $text);
        $link->setAttribute('href', 'https://myanimelist.net/anime/99999999/Removed');

        return $link;
    }

    private static function absolute(string $href): string
    {
        return str_starts_with($href, 'http') ? $href : 'https://myanimelist.net' . $href;
    }
}
