<?php

namespace JikanTest\Helper;

use Jikan\Helper\ImagePlaceholder;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class ImagePlaceholderTest
 *
 * Covers jikan-me/jikan#488: MyAnimeList's "no image" placeholder assets must
 * never be surfaced by the parsers — they map to null instead. The fragment
 * list lives in one place; this test pins the exact URL shapes MAL serves so a
 * future MAL rename breaks the helper's contract here, not silently in a dozen
 * parsers.
 */
class ImagePlaceholderTest extends TestCase
{
    #[Test]
    public function it_matches_every_measured_placeholder_fragment(): void
    {
        $placeholders = [
            // characters & voice actors / people with no image
            'https://cdn.myanimelist.net/images/questionmark_23.gif',
            // resized size-prefix variant with cache-busting query
            'https://cdn.myanimelist.net/r/42x62/images/questionmark_23.gif?s=f7dcbc4a4603d18356d3dfef8abd655c',
            // user search / friend lists
            'https://cdn.myanimelist.net/images/questionmark_50.gif',
            // anime search results with missing art
            'https://cdn.myanimelist.net/images/qm_50.gif',
            // company / producer search
            'https://cdn.myanimelist.net/images/company/placeholder.png',
            // club search
            'https://cdn.myanimelist.net/images/na.gif',
            // club search via the akamai-origin host
            'https://akamai-origin.myanimelist.net/images/na.gif',
            // anime / manga missing art (png + json variants)
            'https://cdn.myanimelist.net/images/na_series_12345.png',
            'https://cdn.myanimelist.net/images/na_series_12345.json',
            // artless anime / manga detail page og:image fallback (site icon)
            'https://cdn.myanimelist.net/img/sp/icon/apple-touch-icon-256.png',
        ];

        foreach ($placeholders as $url) {
            self::assertTrue(
                ImagePlaceholder::isPlaceholder($url),
                sprintf('Expected placeholder detection for %s', $url)
            );
        }
    }

    #[Test]
    public function it_does_not_match_real_image_urls(): void
    {
        $real = [
            'https://cdn.myanimelist.net/images/anime/10/78745.jpg?s=8ea4cb2e8a861e63757d3c05aa5d32c2',
            'https://cdn.myanimelist.net/images/manga/1/209370.jpg?s=2ecbe2700c0d80f4ee08692d7efeac4c',
            'https://cdn.myanimelist.net/images/characters/15/284859.jpg?s=baed7a851bb385725b6ad6bdbfb98a6c',
            'https://cdn.myanimelist.net/s/common/userimages/3b583c98-e76c-4511-9129-00c5978e2775_42x62_i?s=0afb63a35a9ae3ebf01f8454ae3334a3',
            'https://cdn.myanimelist.net/images/voiceactors/1/66163.jpg?s=79dcfe73dbea00556b9e55d2b145447d',
            'https://i.ytimg.com/vi/abc123/default.jpg',
            // similar-looking but real paths must not false-positive
            'https://example.com/gallery/questionmark_logo.png',
            'https://cdn.myanimelist.net/images/questionmark_24.gif',
            // other apple-touch-icon sizes (favicons) are not the og:image fallback
            'https://cdn.myanimelist.net/img/sp/icon/apple-touch-icon-57.png',
            'https://cdn.myanimelist.net/img/sp/icon/apple-touch-icon-180.png',
        ];

        foreach ($real as $url) {
            self::assertFalse(
                ImagePlaceholder::isPlaceholder($url),
                sprintf('Expected no placeholder detection for %s', $url)
            );
        }
    }

    #[Test]
    public function it_handles_null_and_empty_inputs(): void
    {
        self::assertFalse(ImagePlaceholder::isPlaceholder(null));
        self::assertFalse(ImagePlaceholder::isPlaceholder(''));
        self::assertNull(ImagePlaceholder::normalize(null));
    }

    #[Test]
    public function it_normalizes_placeholders_to_null_and_keeps_real_urls(): void
    {
        self::assertNull(
            ImagePlaceholder::normalize('https://cdn.myanimelist.net/images/questionmark_23.gif')
        );

        $real = 'https://cdn.myanimelist.net/images/anime/10/78745.jpg?s=8ea4cb2e8a861e63757d3c05aa5d32c2';
        self::assertSame($real, ImagePlaceholder::normalize($real));
    }
}