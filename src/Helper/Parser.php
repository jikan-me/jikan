<?php

namespace Jikan\Helper;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Parser
 *
 * @package Jikan\Helper
 */
class Parser
{
    private const ALLOWED_NODES = ['p', 'i', 'b', 'br', 'strong', 'u'];

    /**
     * Removes all html elements so the text is left over
     *
     * Works on a detached copy: the crawler passed in (and the page DOM behind it) is left
     * untouched, so later getters and repeated getModel() calls still see the original markup.
     *
     * @param Crawler $crawler
     *
     * @return Crawler
     * @throws \InvalidArgumentException
     */
    public static function removeChildNodes(Crawler $crawler, bool $removeAllNodes = false): Crawler
    {
        if (!$crawler->count()) {
            return $crawler;
        }

        $allowed = $removeAllNodes ? [] : self::ALLOWED_NODES;

        // Copy the node shallowly and deep-copy only the children that survive. Cloning the whole
        // subtree and then deleting most of it is far slower: some callers pass a container
        // holding thousands of elements (e.g. the parent of the synopsis on an anime page).
        $copy = new Crawler();
        foreach ($crawler as $domNode) {
            $clone = $domNode->cloneNode(false);
            foreach ($domNode->childNodes as $child) {
                if ($child->nodeType === XML_ELEMENT_NODE && !\in_array($child->nodeName, $allowed, true)) {
                    continue;
                }
                $clone->appendChild($child->cloneNode(true));
            }
            $copy->add($clone);
        }

        return $copy;
    }

    /**
     * Extract the id from a mal url
     *
     * @param string $url
     *
     * @return int
     */
    public static function idFromUrl(string $url): int
    {
        return (int) preg_replace('#https://myanimelist.net(/\w+/)(\d+).*#', '$2', $url);
    }

    /**
     * Extract a string id from a mal url
     *
     * @param string $url
     *
     * @return string
     */
    public static function stringIdFromUrl(string $url): string
    {
        $urlParts = explode("/", parse_url($url)['path'] ?? '');
        return $urlParts[array_key_last($urlParts)];
    }

    /**
     * Extract club ID from MAL URl
     *
     * @param string $url
     * @return int
     */
    public static function clubIdFromUrl(string $url): int
    {
        return (int) preg_replace('~.*\.php\?cid=([\d]+)$~', '$1', $url);
    }

    /**
     * Extract the last property id from a mal url (e.g episode ID)
     *
     * @param string $url
     *
     * @return int
     */
    public static function suffixIdFromUrl(string $url): int
    {
        return (int) preg_replace('#https://myanimelist.net/.*/(\d+)#', '$1', $url);
    }

    /**
     * @param string $date
     *
     * @return \DateTimeImmutable|null
     */
    public static function parseForumDate(string $date): ?\DateTimeImmutable
    {
        if (!preg_match('/\d{4}/', $date)) {
            $date .= ', ' . Time::now()->format('Y');
        }

        return self::parseDate($date);
    }

    /**
     * @param string $date
     *
     * @return \DateTimeImmutable|null
     */
    public static function parseDate(string $date): ?\DateTimeImmutable
    {
        if (preg_match('/^\d{4}$/', $date)) {
            return \DateTimeImmutable::createFromFormat('!Y-m-d', $date . '-01-01', new \DateTimeZone('UTC'));
        }

        if (preg_match('/(\w{3}), (\d{4})/', $date, $matches)) {
            $monthYear = \DateTimeImmutable::createFromFormat(
                '!M d, Y',
                "{$matches[1]} 01, {$matches[2]}",
                new \DateTimeZone('UTC')
            );
            // "Unknown, 2026" also matches the pattern but is no month: fall through instead of returning false
            if ($monthYear !== false) {
                return $monthYear;
            }
        }

        try {
            return Time::parse($date, new \DateTimeZone('UTC'));
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param string|null $date
     *
     * @return \DateTimeImmutable|null
     */
    public static function parseDateMDY(?string $date): ?\DateTimeImmutable
    {
        if ($date === '-' || $date === null) {
            return null;
        }

        $dateArray = explode('-', $date);

        if ($dateArray[0] === '??' && $dateArray[1] === '??' && $dateArray[2] === Time::now()->format('y')) {
            return null;
        }

        $date = str_replace('??', '01', $date);

        $parsed = \DateTimeImmutable::createFromFormat('!m-d-y', $date, new \DateTimeZone('UTC'));

        return $parsed ? self::pivotTwoDigitYear($parsed) : null;
    }

    /**
     * @param string|null $date
     *
     * @return \DateTimeImmutable|null
     */
    public static function parseDateDMY(?string $date): ?\DateTimeImmutable
    {
        if ($date === '-' || $date === null) {
            return null;
        }

        $dateArray = explode('-', $date);

        if ($dateArray[0] === '??' && $dateArray[1] === '??' && $dateArray[2] === Time::now()->format('y')) {
            return null;
        }

        $date = str_replace('??', '01', $date);

        $parsed = \DateTimeImmutable::createFromFormat('!d-m-y', $date, new \DateTimeZone('UTC'));

        return $parsed ? self::pivotTwoDigitYear($parsed) : null;
    }

    /**
     * Current and last page from a list page's `div.pagination`, which links pages as `?page=N` (genres) or
     * `?p=N` (news, featured articles) and marks the current one with the `current` class.
     *
     * @return array{current: int, last: int} both 1 when the page has no pagination
     */
    public static function pagination(Crawler $page): array
    {
        $links = $page->filterXPath('//div[contains(@class, "pagination")]/a[contains(@class, "link")]');
        $current = 1;
        $last = 1;
        foreach ($links as $link) {
            if (!$link instanceof \DOMElement || !preg_match('~[?&](?:page|p)=(\d+)~', $link->getAttribute('href'), $m)) {
                continue;
            }
            $last = max($last, (int) $m[1]);
            if (str_contains(' ' . $link->getAttribute('class') . ' ', ' current ')) {
                $current = (int) $m[1];
            }
        }

        return ['current' => $current, 'last' => $last];
    }

    /**
     * PHP reads two-digit years 00-69 as 2000-2069, so a 1961 title came back as 2061. MyAnimeList lists dates
     * at most a few years ahead, so a year more than 10 years in the future belongs to the previous century.
     */
    private static function pivotTwoDigitYear(\DateTimeImmutable $date): \DateTimeImmutable
    {
        if ((int) $date->format('Y') > (int) Time::now()->format('Y') + 10) {
            return $date->modify('-100 years');
        }

        return $date;
    }

    /**
     * @param string $date
     *
     * @return \DateTimeImmutable|null
     * @throws \Exception
     */
    public static function parseDateMDYReadable(string $date): ?\DateTimeImmutable
    {
        $date = str_replace('  ', ' ', $date);

        try {
            if (preg_match('~[a-zA-z]+ \d+, \d{4}~', $date)) {
                return Time::parse($date, new \DateTimeZone('UTC'));
            }

            // Parses invalid dates without days, e.g: May, 1979
            // Converts it to May 01, 1979 otherwise DateTimeImmutable will use today's day
            if (preg_match('~^([a-zA-z]+), (\d{4})$~', $date, $matches)) {
                return Time::parse("$matches[1] 01, $matches[2]", new \DateTimeZone('UTC'));
            }
        } catch (\Exception) {
            // shaped like a date but not one ("Unknown, 2026")
            return null;
        }

        return null;
    }

    /**
     * @param string $dateTime
     * @return \DateTimeImmutable|null
     */
    public static function parseDateTimePST(string $dateTime): ?\DateTimeImmutable
    {
        try {
            $malTimeZone = new \DateTimeZone('America/Los_Angeles'); //
            $parsedDateTime = Time::parse($dateTime, $malTimeZone);
            return $parsedDateTime->setTimezone(new \DateTimeZone('UTC'));
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param Crawler $crawler
     *
     * @return null|string
     * @throws \InvalidArgumentException
     */
    public static function textOrNull(Crawler $crawler): ?string
    {
        if (!$crawler->count()) {
            return null;
        }

        return $crawler->text();
    }

    /**
     * @param string
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function parseImageQuality(string $imageUrl): string
    {
        // adding `v` prefix returns a very small thumbnail, as opposed to adding `l`
        $imageUrl = str_replace(['v.jpg', 't.jpg', 'l.jpg'], '.jpg', $imageUrl);
        return preg_replace('~/r/\d+x\d+~', '', $imageUrl);
    }

    /**
     * @param string
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function parseImageThumbToHQ(string $imageUrl): string
    {
        return str_replace(['thumbs/', '_thumb'], '', $imageUrl);
    }

    /**
     * @param string $duration
     * @return int|null
     */
    public static function parseDurationToSeconds(string $duration): ?int
    {
        preg_match('~([0-9]{2}):([0-9]{2}):([0-9]{2})~', $duration, $match);
        if (empty($match)) {
            return null;
        }

        [, $hours, $minutes, $seconds] = $match;

        return ($hours * 60 * 60) + ($minutes * 60) + $seconds;
    }

    /**
     * A progress cell as MyAnimeList prints it: "5/12", "5/?" (total not known yet), "-/12", a bare "-" (nothing
     * yet, no slash at all) or empty. Each number is null when the cell shows "-", "?" or nothing.
     *
     * @return array{0: ?int, 1: ?int} [read, total]
     */
    public static function parseProgress(string $text): array
    {
        $parts = explode('/', str_replace([' ', ','], '', JString::cleanse($text)), 2);
        $number = static fn(?string $part): ?int => $part !== null && ctype_digit($part) ? (int) $part : null;

        return [$number($parts[0]), $number($parts[1] ?? null)];
    }
}
