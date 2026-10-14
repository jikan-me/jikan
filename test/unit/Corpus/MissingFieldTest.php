<?php

namespace JikanTest\Corpus;

use Jikan\Parser\Anime\AnimeParser;
use Jikan\Parser\Manga\MangaParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Synthetic variants: what happens when MyAnimeList drops one sidebar row?
 *
 * Takes each recorded anime/manga detail page in the corpus, removes one "Label:" row from the sidebar at a time
 * (through the DOM), parses the page again and checks that parsing still succeeds and that only the fields that
 * row feeds have changed. A parser that crashes, or fills a different field, when a row is missing fails here.
 * The pages are real recordings with one row taken out; no request is made.
 */
class MissingFieldTest extends TestCase
{
    /** The variants whose recorded page is an anime or manga detail page, with the parser for it. */
    private const PAGES = [
        'anime-1' => AnimeParser::class,
        'anime-21' => AnimeParser::class,
        'anime-199' => AnimeParser::class,
        'anime-5114' => AnimeParser::class,
        'anime-11617' => AnimeParser::class,
        'manga-1' => MangaParser::class,
        'manga-2' => MangaParser::class,
        'manga-9115' => MangaParser::class,
    ];

    /**
     * Model properties a sidebar row may feed. A row whose label is not listed is an alternative title in some
     * language ("French:", "German:", ...), which only feeds `titles`.
     */
    private const FEEDS = [
        'Synonyms:' => ['titleSynonyms', 'titles'],
        'Japanese:' => ['titleJapanese', 'titles'],
        'English:' => ['titleEnglish', 'titles'],
        'Type:' => ['type'],
        'Episodes:' => ['episodes'],
        'Status:' => ['status', 'airing', 'publishing'],
        'Aired:' => ['aired'],
        'Published:' => ['published'],
        'Premiered:' => ['premiered'],
        'Broadcast:' => ['broadcast'],
        'Producers:' => ['producers'],
        'Licensors:' => ['licensors'],
        'Studios:' => ['studios'],
        'Source:' => ['source'],
        'Genres:' => ['genres'],
        'Genre:' => ['genres'],
        'Explicit Genres:' => ['explicitGenres'],
        'Explicit Genre:' => ['explicitGenres'],
        'Themes:' => ['themes'],
        'Theme:' => ['themes'],
        'Demographic:' => ['demographics'],
        'Demographics:' => ['demographics'],
        'Duration:' => ['duration'],
        'Rating:' => ['rating'],
        'Score:' => ['score', 'scoredBy'],
        'Ranked:' => ['rank'],
        'Popularity:' => ['popularity'],
        'Members:' => ['members'],
        'Favorites:' => ['favorites'],
        'Volumes:' => ['volumes'],
        'Chapters:' => ['chapters'],
        'Serialization:' => ['serializations'],
        'Authors:' => ['authors'],
    ];

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function rows(): iterable
    {
        foreach (array_keys(self::PAGES) as $id) {
            [$url, $html] = self::page($id);
            $page = new Crawler(null, $url);
            $page->addHtmlContent($html);
            $labels = [];
            foreach ($page->filterXPath('//span[contains(@class, "dark_text")]') as $span) {
                $labels[trim($span->textContent)] = true;
            }
            foreach (array_keys($labels) as $label) {
                yield "$id without $label" => [$id, $label];
            }
        }
    }

    #[Test]
    #[DataProvider('rows')]
    public function a_missing_sidebar_row_only_empties_its_own_fields(string $id, string $label): void
    {
        [$url, $html] = self::page($id);
        $parser = self::PAGES[$id];

        $before = self::properties(new $parser(self::crawler($url, $html))->getModel());

        $page = self::crawler($url, $html);
        $span = $page->filterXPath(sprintf('//span[contains(@class, "dark_text")][normalize-space(text())="%s"]', $label));
        $row = $span->getNode(0)->parentNode;
        $row->parentNode->removeChild($row);

        $after = self::properties(new $parser($page)->getModel());

        $changed = array_keys(array_filter($before, static fn ($v, $k): bool => ($after[$k] ?? null) !== $v, ARRAY_FILTER_USE_BOTH));
        $allowed = self::FEEDS[$label] ?? ['titles'];
        self::assertSame(
            [],
            array_values(array_diff($changed, $allowed)),
            "Removing \"$label\" from $id changed fields that row does not feed."
        );
    }

    private static function crawler(string $url, string $html): Crawler
    {
        $crawler = new Crawler(null, $url);
        $crawler->addHtmlContent($html);

        return $crawler;
    }

    /**
     * @return array{string, string} the recorded page's URL and HTML
     */
    private static function page(string $id): array
    {
        return Variants::page($id);
    }

    /**
     * @return array<string, string> top-level model properties, serialised so they compare by value
     */
    private static function properties(object $model): array
    {
        $out = [];
        for ($class = new \ReflectionObject($model); $class; $class = $class->getParentClass()) {
            foreach ($class->getProperties() as $p) {
                if (!$p->isStatic() && $p->isInitialized($model) && !isset($out[$p->getName()])) {
                    $out[$p->getName()] = serialize($p->getValue($model));
                }
            }
        }

        return $out;
    }
}
