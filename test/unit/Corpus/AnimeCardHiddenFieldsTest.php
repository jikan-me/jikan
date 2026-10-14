<?php

namespace JikanTest\Corpus;

use Jikan\Parser\Common\AnimeCardParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Every anime card (genre, producer, seasonal and schedule pages) carries two machine-readable fields:
 * a "js-anime-type-N" class and a hidden ".js-start_date" of "YYYYMMDD" (00 for unknown parts). Producer
 * cards show neither the type nor the date otherwise, and genre cards show only the year.
 */
class AnimeCardHiddenFieldsTest extends TestCase
{
    private const CARD_PAGES = ['anime-genre-1-p1', 'anime-genre-1-p2', 'seasonal-1990-spring', 'seasonal-later', 'producer-1-p1'];

    #[Test]
    public function the_type_class_agrees_with_the_type_the_card_shows(): void
    {
        $types = new \ReflectionClassConstant(AnimeCardParser::class, 'TYPES')->getValue();
        $checked = 0;
        foreach (['anime-genre-1-p1', 'anime-genre-1-p2', 'seasonal-1990-spring', 'seasonal-later'] as $id) {
            foreach (self::cards($id) as [$number, $card]) {
                $shown = self::shownType($card);
                if ($number === 0 || $shown === null) {
                    continue;
                }
                // Seasonal pages file TV Specials under their "Special" section; the class is the precise one.
                $expected = $number === 9 && $shown === 'Special' ? 'Special' : $types[$number];
                self::assertSame($expected, $shown, "$id: js-anime-type-$number");
                $checked++;
            }
        }
        self::assertGreaterThan(600, $checked);
    }

    #[Test]
    public function every_card_with_a_type_class_reports_it(): void
    {
        $types = new \ReflectionClassConstant(AnimeCardParser::class, 'TYPES')->getValue();
        foreach (self::CARD_PAGES as $id) {
            foreach (self::cards($id) as [$number, $card]) {
                if ($number === 0) {
                    // Unknown type: the card's other sources decide (seasonal pages have an "Unknown" section).
                    continue;
                }
                self::assertSame($types[$number], new AnimeCardParser($card)->getType(), "$id: js-anime-type-$number");
            }
        }
    }

    #[Test]
    public function every_card_with_a_known_start_date_has_an_air_date(): void
    {
        foreach (self::CARD_PAGES as $id) {
            foreach (self::cards($id) as [, $card]) {
                $raw = trim($card->filter('.js-start_date')->text());
                $date = new AnimeCardParser($card)->getAirDates();
                if (str_starts_with($raw, '0000')) {
                    self::assertNull($date, "$id: $raw");
                    continue;
                }
                self::assertNotNull($date, "$id: $raw");
                // Midnight in Japan on that day, as the visible dates are read.
                $expected = substr($raw, 0, 4) . '-' . (substr($raw, 4, 2) === '00' ? '01' : substr($raw, 4, 2))
                    . '-' . (substr($raw, 6, 2) === '00' ? '01' : substr($raw, 6, 2));
                self::assertSame($expected . ' 00:00', $date->setTimezone(new \DateTimeZone('Asia/Tokyo'))->format('Y-m-d H:i'), "$id: $raw");
            }
        }
    }

    /**
     * @return iterable<string, array{string, ?string}>
     */
    public static function startDates(): iterable
    {
        yield 'full date' => ['20130407', '2013-04-07'];
        yield 'month and year' => ['20271000', '2027-10-01'];
        yield 'year only' => ['20270000', '2027-01-01'];
        yield 'nothing known' => ['00000000', null];
    }

    #[Test]
    #[DataProvider('startDates')]
    public function a_start_date_with_unknown_parts_falls_on_the_first(string $raw, ?string $expected): void
    {
        [, $card] = self::cards('seasonal-later')[0];
        $card->filter('.js-start_date')->getNode(0)->textContent = $raw;

        self::assertSame($expected, new AnimeCardParser($card)->getAirDates()?->setTimezone(new \DateTimeZone('Asia/Tokyo'))->format('Y-m-d'));
    }

    #[Test]
    public function without_the_hidden_date_a_bare_year_is_not_read_as_a_time_today(): void
    {
        // "2027" on its own used to parse as 20:27 today.
        [, $card] = self::cards('seasonal-later')[0];
        $hidden = $card->filter('.js-start_date')->getNode(0);
        $hidden->parentNode->removeChild($hidden);
        $card->filterXPath('//div/div[2]/div[2]/span[contains(@class, "item")][1]')->getNode(0)->textContent = '2027';

        self::assertSame('2027-01-01', new AnimeCardParser($card)->getAirDates()?->setTimezone(new \DateTimeZone('Asia/Tokyo'))->format('Y-m-d'));
    }

    /** The type the card shows: its info line ("TV, 2013") or the header of its seasonal section ("TV (New)"). */
    private static function shownType(Crawler $card): ?string
    {
        $info = $card->filterXPath('//div[contains(@class, "info")]/span[contains(@class, "item")][1]');
        if ($info->count() && preg_match('/^\s*([A-Za-z][A-Za-z .\-]*?)\s*,/', $info->text(), $m)) {
            return $m[1];
        }
        for ($section = $card->getNode(0)->parentNode; $section instanceof \DOMElement; $section = $section->parentNode) {
            foreach ($section->childNodes as $child) {
                if ($child instanceof \DOMElement && str_contains(' ' . $child->getAttribute('class') . ' ', ' anime-header ')) {
                    return trim(preg_replace('/\s*\(.*\)\s*$/', '', trim($child->textContent)));
                }
            }
        }

        return null;
    }

    /**
     * @return list<array{int, Crawler}> each card with its js-anime-type number (0 when unknown)
     */
    private static function cards(string $id): array
    {
        $cards = [];
        foreach (Variants::crawler($id)->filter('div.seasonal-anime') as $node) {
            preg_match('~js-anime-type-(\d+)~', $node->getAttribute('class'), $m);
            $cards[] = [(int) ($m[1] ?? 0), new Crawler($node, 'https://myanimelist.net/')];
        }

        return $cards;
    }
}
