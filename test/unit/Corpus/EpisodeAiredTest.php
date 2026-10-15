<?php

namespace JikanTest\Corpus;

use Jikan\Parser\Anime\AnimeEpisodeParser;
use Jikan\Parser\Anime\EpisodeListItemParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\DomCrawler\Crawler;

/**
 * jikan-rest#567: an episode's air date differed between the episode list and the single-episode page
 * ("1999-10-20T00:00:00+00:00" against "1999-10-20T00:00:00+09:00"). The single-episode page prints
 * "Aired: Oct 20, 1999(JST)" and the "(JST)" was read as a UTC+9 offset. Both now give UTC midnight of the
 * printed day, as the anime's own aired dates do.
 */
class EpisodeAiredTest extends TestCase
{
    #[Test]
    public function the_list_and_the_single_episode_page_agree_on_the_air_date(): void
    {
        $listed = new EpisodeListItemParser(Variants::crawler('anime-episodes-21-p1')->filter('tr.episode-list-data')->first());
        $single = new AnimeEpisodeParser(Variants::crawler('anime-episode-21-1'));

        self::assertSame('1999-10-20T00:00:00+00:00', $listed->getAired()?->format('c'));
        self::assertEquals($listed->getAired(), $single->getAired());
        self::assertSame($listed->getAired()?->format('c'), $single->getAired()?->format('c'));
    }

    /**
     * @return iterable<string, array{string, ?string}>
     */
    public static function printedDates(): iterable
    {
        yield 'with the JST marker' => ['Aired: Oct 20, 1999(JST)', '1999-10-20T00:00:00+00:00'];
        yield 'with a space before the marker' => ['Aired: Apr 25, 2025 (JST)', '2025-04-25T00:00:00+00:00'];
        yield 'without a marker' => ['Aired: Dec 1, 2004', '2004-12-01T00:00:00+00:00'];
        yield 'month and year only' => ['Aired: Dec, 2004(JST)', '2004-12-01T00:00:00+00:00'];
    }

    #[Test]
    #[DataProvider('printedDates')]
    public function the_printed_day_is_utc_midnight(string $printed, string $expected): void
    {
        $page = Variants::crawler('anime-episode-21-1');
        $node = $page->filterXPath('//div[contains(@class, "di-tc pt4 pb4 pl8 pr8 ar fn-grey2")]')->getNode(0);
        $node->textContent = $printed;

        $aired = new AnimeEpisodeParser(new Crawler($page->getNode(0)->ownerDocument, $page->getUri()))->getAired();

        self::assertSame($expected, $aired?->format('c'));
    }
}
