<?php

namespace Jikan\Helper;

/**
 * Detects MyAnimeList placeholder images.
 *
 * MyAnimeList serves a set of generic, non-artwork images for entities that
 * have no picture of their own (characters without art, voice actors, people,
 * companies, users with the default avatar, and anime/manga whose cover was
 * never uploaded or was removed). These placeholders carry no information, so
 * parsers must not surface them: the correct value is `null`, meaning "this
 * entity has no image".
 *
 * The fragments below were measured against live cdn.myanimelist.net pages and
 * the jikan recording fixtures (jikan-me/jikan#488):
 *
 *   /images/questionmark_23.gif        characters & voice actors / people
 *   /images/questionmark_50.gif        user search, friend lists
 *   /images/qm_50.gif                  anime search results (missing art)
 *   /images/company/placeholder.png    company / producer search
 *   /images/na.gif                     club search
 *   /images/na_series_*.png|json       anime / manga missing art
 *
 * Matching is done against URL *fragments* on purpose: MyAnimeList rewrites
 * these assets with a `/r/WxH/` size prefix and a `?s=` cache-busting query
 * (e.g. `https://cdn.myanimelist.net/r/42x62/images/questionmark_23.gif?s=…`),
 * and can host them on either cdn.myanimelist.net or akamai-origin
 * .myanimelist.net. A path-based substring match therefore stays
 * false-positive-free while covering every host/query variant.
 */
final class ImagePlaceholder
{
    private const PLACEHOLDER_FRAGMENTS = [
        '/images/questionmark_23.gif',
        '/images/questionmark_50.gif',
        '/images/qm_50.gif',
        '/images/company/placeholder.png',
        '/images/na.gif',
        '/images/na_series_',
    ];

    /**
     * Whether the given URL is a MyAnimeList "no image" placeholder.
     *
     * @param string|null $url
     * @return bool
     */
    public static function isPlaceholder(?string $url): bool
    {
        if ($url === null || $url === '') {
            return false;
        }

        foreach (self::PLACEHOLDER_FRAGMENTS as $fragment) {
            if (str_contains($url, $fragment)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns null when the URL is a placeholder, the URL unchanged otherwise.
     *
     * Central helper for every spot that turns an image URL into an entity's
     * image value: image resource factories, raw-string parser getters and the
     * raw-string model properties all funnel through here so the placeholder
     * set lives in exactly one place.
     *
     * @param string|null $url
     * @return string|null
     */
    public static function normalize(?string $url): ?string
    {
        return self::isPlaceholder($url) ? null : $url;
    }
}
