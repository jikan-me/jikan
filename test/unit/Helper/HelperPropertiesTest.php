<?php

namespace JikanTest\Helper;

use Jikan\Helper\ImagePlaceholder;
use Jikan\Helper\JString;
use Jikan\Helper\Media;
use Jikan\Helper\Parser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Property tests for the shared helpers every parser uses: instead of a few hand-picked examples, each rule is
 * checked against a few hundred generated inputs (junk text, emoji, CJK, NBSP, HTML fragments, malformed dates).
 * The seed is fixed, so a failure is reproducible.
 *
 * Not checked on purpose: JString::cleanse() is not idempotent. It turns <br> into a newline, and a second pass
 * strips that newline as a control character. Changing it would change the newlines in all scraped text.
 */
class HelperPropertiesTest extends TestCase
{
    private const SEED = 20261014;

    private const PIECES = [
        'a', 'Z', '0', '9', ' ', "\t", "\n", '-', '/', '?', ',', '.', ':', '<br>', '<b>', '</b>', '&amp;', '&#39;',
        "\u{00A0}", '日本', 'ルルーシュ', '😀', "\u{1341A}", 'Jan', 'Oct 13,', '2026', '??', '12:04 PM', 'Yesterday',
        'hours ago', 'https://myanimelist.net/anime/', '123', '/Name', '<a href="x">', 'hr.', 'min.', 'sec.', 'eps',
        'Not available', 'Unknown', 'to',
    ];

    /**
     * @return list<string>
     */
    private static function inputs(): array
    {
        mt_srand(self::SEED);
        $inputs = ['', ' ', '-', '??-??-??', '01-01-61', '??-05-20', '1--2', '2026', 'Oct 13, 2026', 'Unknown, 2026',
            'Yesterday, 9:25 PM', '24 min. per ep.', '1 hr. 30 min.', 'https://myanimelist.net/anime/1/Cowboy_Bebop'];
        for ($i = 0; $i < 400; $i++) {
            $s = '';
            for ($n = mt_rand(0, 8); $n > 0; $n--) {
                $s .= self::PIECES[array_rand(self::PIECES)];
            }
            $inputs[] = $s;
        }

        return $inputs;
    }

    #[Test]
    public function cleanse_returns_trimmed_valid_utf8(): void
    {
        foreach (self::inputs() as $in) {
            $out = JString::cleanse($in);
            self::assertTrue(mb_check_encoding($out, 'UTF-8'), 'Invalid UTF-8 from ' . json_encode($in));
            self::assertSame(trim($out), $out, 'Untrimmed result from ' . json_encode($in));
        }
    }

    #[Test]
    public function date_helpers_return_a_date_or_null_for_any_text(): void
    {
        foreach (['parseDate', 'parseForumDate', 'parseDateMDY', 'parseDateDMY', 'parseDateMDYReadable', 'parseDateTimePST'] as $helper) {
            foreach (self::inputs() as $in) {
                $out = Parser::$helper($in);
                self::assertTrue($out === null || $out instanceof \DateTimeImmutable, "Parser::$helper(" . json_encode($in) . ')');
            }
        }
    }

    #[Test]
    public function id_helpers_accept_any_text(): void
    {
        foreach (self::inputs() as $in) {
            Parser::idFromUrl($in);
            Parser::clubIdFromUrl($in);
            Parser::suffixIdFromUrl($in);
            Parser::stringIdFromUrl($in);
            Media::youtubeIdFromUrl($in);
        }
        $this->addToAssertionCount(1);
    }

    #[Test]
    public function id_from_url_reads_back_the_id(): void
    {
        mt_srand(self::SEED);
        for ($i = 0; $i < 200; $i++) {
            $id = mt_rand(1, 9_999_999);
            self::assertSame($id, Parser::idFromUrl("https://myanimelist.net/anime/$id/Some_Title"));
            self::assertSame($id, Parser::idFromUrl("https://myanimelist.net/manga/$id"));
        }
    }

    #[Test]
    public function duration_is_null_or_not_negative(): void
    {
        foreach (self::inputs() as $in) {
            $seconds = Parser::parseDurationToSeconds($in);
            self::assertTrue($seconds === null || $seconds >= 0, 'Negative duration from ' . json_encode($in));
        }
    }

    #[Test]
    public function placeholder_normalisation_is_idempotent(): void
    {
        self::assertNull(ImagePlaceholder::normalize(null));
        foreach (self::inputs() as $in) {
            $once = ImagePlaceholder::normalize($in);
            self::assertSame($once, ImagePlaceholder::normalize($once));
        }
    }
}
