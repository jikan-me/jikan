<?php

namespace JikanTest\Corpus;

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
 * Synthetic variants: MyAnimeList sometimes lists a related entry as an empty link (<a href="..."></a>), for
 * example when the entry was removed. An empty link is not a related entry; a relation whose only link is empty
 * has no entries. Each case adds empty links to the related-entries table of a recorded page through the DOM.
 */
class EmptyRelatedLinkTest extends TestCase
{
    /**
     * @return iterable<string, array{string, class-string, string, string, string}>
     */
    public static function pages(): iterable
    {
        // [variant, parser, its related-entries getter, XPath of a related-entries row, XPath of the links cell in it]
        yield 'anime' => ['anime-21', AnimeParser::class, 'getRelated', '//table[contains(@class, "entries-table")]/tbody/tr', 'td[2]'];
        yield 'manga' => ['manga-2', MangaParser::class, 'getMangaRelated', '//table[contains(@class, "entries-table")]/tbody/tr', 'td[2]'];
        yield 'news' => ['news-70748275', NewsParser::class, 'getRelatedEntries', '//table[contains(@class, "news-related-database")]/tbody/tr', 'td[2]'];
        yield 'article' => ['article-123', ArticleParser::class, 'getRelatedEntries', '//*[contains(text(), "Related Database Entries")]/following-sibling::table/tbody/tr', 'td[2]'];
    }

    #[Test]
    #[DataProvider('pages')]
    public function an_empty_link_is_not_a_related_entry(string $id, string $parser, string $getter, string $rows, string $cell): void
    {
        $page = Variants::crawler($id);
        $before = self::urls(new $parser($page)->$getter());

        $row = $page->filterXPath($rows)->getNode(0);
        self::assertNotNull($row, "$id has related entries");
        self::cell($row, $cell)->appendChild(self::emptyLink($row));

        self::assertSame($before, self::urls(new $parser($page)->$getter()));
    }

    #[Test]
    #[DataProvider('pages')]
    public function a_relation_whose_only_link_is_empty_has_no_entries(string $id, string $parser, string $getter, string $rows, string $cell): void
    {
        $page = Variants::crawler($id);
        $row = $page->filterXPath($rows)->getNode(0);
        $relation = trim(str_replace(':', '', $row->firstElementChild->textContent));
        $links = self::cell($row, $cell);
        while ($links->firstChild) {
            $links->removeChild($links->firstChild);
        }
        $links->appendChild(self::emptyLink($row));

        $related = new $parser($page)->$getter();
        self::assertArrayHasKey($relation, $related);
        self::assertSame([], $related[$relation]);
    }

    private static function cell(\DOMElement $row, string $cell): \DOMElement
    {
        $node = new \DOMXPath($row->ownerDocument)->query($cell, $row)->item(0);
        self::assertInstanceOf(\DOMElement::class, $node);

        return $node;
    }

    private static function emptyLink(\DOMElement $row): \DOMElement
    {
        // Whitespace only, as MyAnimeList's markup often indents inside the tag: still empty.
        $link = $row->ownerDocument->createElement('a', "\n    ");
        $link->setAttribute('href', 'https://myanimelist.net/anime/99999999/Removed');

        return $link;
    }

    /**
     * @param array<string, list<MalUrl>> $related
     * @return array<string, list<string>>
     */
    private static function urls(array $related): array
    {
        return array_map(static fn (array $links): array => array_map(static fn (MalUrl $u): string => $u->getUrl(), $links), $related);
    }
}
