<?php

namespace JikanTest\Corpus;

use Jikan\Parser\Common\AnimeCardParser;
use Jikan\Parser\Magazine\MagazineListItemParser;
use Jikan\Parser\Producer\ProducerListItemParser;
use Jikan\Parser\Producer\ProducerParser;
use Jikan\Parser\Recommendations\UserRecommendationsParser;
use Jikan\Parser\User\Profile\AnimeStatsParser;
use Jikan\Parser\User\Profile\MangaStatsParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Synthetic variants: numbers of 1,000 and more.
 *
 * MyAnimeList prints large counts with a thousands separator ("12,345"). On the recorded pages many of these
 * fields happen to be under 1,000, so nothing showed that the separator is handled. Each test takes a recorded
 * page, writes "12,345" into the field through the DOM, and checks the parser returns 12345 (not 12).
 */
class ThousandsSeparatorTest extends TestCase
{
    #[Test]
    public function user_profile_stats_read_numbers_with_separators(): void
    {
        $page = Variants::crawler('user-profile-xinil');
        self::write($page, '//a[contains(@class, "watching") or contains(@class, "reading") or contains(@class, "completed")
            or contains(@class, "on_hold") or contains(@class, "dropped") or contains(@class, "plan_to")]/following-sibling::span', '12,345');
        self::write($page, '//ul[@class="stats-data fl-r"]/li/span[2]', '12,345');
        self::write($page, '//div[@class="di-tc al pl8 fs12 fw-b"]', '1,234.5');

        foreach ([new AnimeStatsParser($page), new MangaStatsParser($page)] as $parser) {
            foreach (get_class_methods($parser) as $getter) {
                if (in_array($getter, ['getModel', 'getMeanScore'], true) || !str_starts_with($getter, 'get')) {
                    continue;
                }
                $expected = str_starts_with($getter, 'getDays') ? 1234.5 : 12345;
                self::assertSame($expected, $parser->$getter(), $parser::class . "::$getter()");
            }
        }
    }

    #[Test]
    public function magazine_and_producer_counts_read_numbers_with_separators(): void
    {
        foreach (['magazines' => MagazineListItemParser::class, 'producers' => ProducerListItemParser::class] as $id => $parser) {
            $page = Variants::crawler($id);
            $link = $page->filter('a.genre-name-link')->first();
            self::write($link, '.', 'Some Studio (12,345)');
            self::assertSame(12345, new $parser($link)->getCount(), $id);
        }
    }

    #[Test]
    public function a_producer_anime_count_reads_numbers_with_separators(): void
    {
        $page = Variants::crawler('producer-1-p1');
        self::write($page, '//*[@id="content"]/div[2]/div[contains(@class, "navi-seasonal")]/div/ul/li[1]', 'All (12,345)');
        self::assertSame(12345, new ProducerParser($page)->getAnimeCount());
    }

    #[Test]
    public function an_anime_card_member_count_reads_numbers_with_separators(): void
    {
        $card = Variants::crawler('anime-genre-1-p1')->filter('div.seasonal-anime')->first();
        self::write($card, '//div[contains(@class, "information")]/div/div/div[2]', '12,345');
        self::assertSame(12345, new AnimeCardParser($card)->getMembers());
    }

    #[Test]
    public function user_recommendations_count_pages_from_a_total_with_separators(): void
    {
        $page = Variants::crawler('user-recommendations-datrandomdude');
        self::write($page, '//*[contains(text(), "Total Recommendations:")]', 'Total Recommendations: 1,234');
        // 1,234 recommendations at 30 per page.
        self::assertEquals(42, new UserRecommendationsParser($page)->getLastPage());
    }

    /**
     * Replaces the number in each matched element: its last non-blank text node, so a label inside the element
     * (such as <span>Days:</span>) is kept. Fails if nothing matches, so a stale XPath cannot pass silently.
     */
    private static function write(Crawler $crawler, string $xpath, string $text): void
    {
        $nodes = $xpath === '.' ? $crawler : $crawler->filterXPath($xpath);
        self::assertGreaterThan(0, $nodes->count(), "Nothing matches $xpath");
        foreach ($nodes as $element) {
            $target = null;
            foreach ($element->childNodes as $child) {
                if ($child->nodeType === XML_TEXT_NODE && trim($child->nodeValue) !== '') {
                    $target = $child;
                }
            }
            if ($target === null) {
                $element->textContent = $text;
                continue;
            }
            $target->nodeValue = $text;
        }
    }
}
