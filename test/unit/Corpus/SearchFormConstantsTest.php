<?php

namespace JikanTest\Corpus;

use Jikan\Helper\Constants;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * The Constants::SEARCH_* filter values match the options of MyAnimeList's own search form.
 *
 * Search requests send these numbers as they are, so a stale constant silently filters by the wrong thing
 * (SEARCH_MANGA_NOVEL used to send 2, which MyAnimeList reads as Light Novel). The recorded search pages carry
 * the form, so each group is compared to its <select>: every option has a constant with the same label, and
 * no constant points at a value the form does not offer.
 */
class SearchFormConstantsTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string, array<string, int>}>
     */
    public static function filters(): iterable
    {
        yield 'anime type' => ['anime-search-fate', 'type', [
            'TV' => Constants::SEARCH_ANIME_TV,
            'OVA' => Constants::SEARCH_ANIME_OVA,
            'Movie' => Constants::SEARCH_ANIME_MOVIE,
            'Special' => Constants::SEARCH_ANIME_SPECIAL,
            'ONA' => Constants::SEARCH_ANIME_ONA,
            'Music' => Constants::SEARCH_ANIME_MUSIC,
            'CM' => Constants::SEARCH_ANIME_CM,
            'PV' => Constants::SEARCH_ANIME_PV,
            'TV Special' => Constants::SEARCH_ANIME_TV_SPECIAL,
        ]];
        yield 'manga type' => ['manga-search-monster', 'type', [
            'Manga' => Constants::SEARCH_MANGA_MANGA,
            'Light Novel' => Constants::SEARCH_MANGA_LIGHT_NOVEL,
            'Novel' => Constants::SEARCH_MANGA_NOVEL,
            'One-shot' => Constants::SEARCH_MANGA_ONESHOT,
            'Doujinshi' => Constants::SEARCH_MANGA_DOUJIN,
            'Manhwa' => Constants::SEARCH_MANGA_MANHWA,
            'Manhua' => Constants::SEARCH_MANGA_MANHUA,
        ]];
        yield 'anime status' => ['anime-search-fate', 'status', [
            'Currently Airing' => Constants::SEARCH_ANIME_STATUS_AIRING,
            'Finished Airing' => Constants::SEARCH_ANIME_STATUS_FINISHED_AIRING,
            'Not yet aired' => Constants::SEARCH_ANIME_STATUS_TO_BE_AIRD,
        ]];
        yield 'manga status' => ['manga-search-monster', 'status', [
            'Publishing' => Constants::SEARCH_MANGA_STATUS_PUBLISHING,
            'Finished' => Constants::SEARCH_MANGA_STATUS_FINISHED_PUBLISHING,
            'Not yet published' => Constants::SEARCH_MANGA_STATUS_TO_BE_PUBLISHED,
            'On Hiatus' => Constants::SEARCH_MANGA_STATUS_ON_HIATUS,
            'Discontinued' => Constants::SEARCH_MANGA_STATUS_DISCONTINUED,
        ]];
        yield 'anime rating' => ['anime-search-fate', 'r', [
            'G - All Ages' => Constants::SEARCH_ANIME_RATING_G,
            'PG - Children' => Constants::SEARCH_ANIME_RATING_PG,
            'PG-13 - Teens 13 or older' => Constants::SEARCH_ANIME_RATING_PG13,
            'R - 17+ (violence & profanity)' => Constants::SEARCH_ANIME_RATING_R17,
            'R+ - Mild Nudity' => Constants::SEARCH_ANIME_RATING_R,
            'Rx - Hentai' => Constants::SEARCH_ANIME_RATING_RX,
        ]];
    }

    /**
     * @param array<string, int> $constants option label => constant value
     */
    #[Test]
    #[DataProvider('filters')]
    public function the_constants_match_the_search_form(string $id, string $select, array $constants): void
    {
        // The site-wide search box has its own select[name=type]; the filter form's options are numeric.
        $options = [];
        foreach (Variants::crawler($id)->filter("select[name=\"$select\"] option") as $option) {
            $value = $option->getAttribute('value');
            if (ctype_digit($value) && $value !== '0') {
                $options[trim($option->textContent)] = (int) $value;
            }
        }
        ksort($options);
        ksort($constants);

        self::assertSame($options, $constants);
    }
}
