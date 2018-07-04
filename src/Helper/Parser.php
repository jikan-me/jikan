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
    private const ALLOWED_NODES = ['p', 'i', 'b', 'br', 'strong'];

    /**
     * Removes all html elements so the text is left over
     *
     * @param Crawler $crawler
     *
     * @return Crawler
     * @throws \InvalidArgumentException
     */
    public static function removeChildNodes(Crawler $crawler): Crawler
    {
        if (!$crawler->count()) {
            return $crawler;
        }
        $crawler->children()->each(
            function (Crawler $crawler) {
                $node = $crawler->getNode(0);
                if ($node === null || $node->nodeType === 3 || \in_array($node->nodeName, self::ALLOWED_NODES, true)) {
                    return;
                }
                $node->parentNode->removeChild($node);
            }
        );

        return $crawler;
    }

    /**
     * Extract the id from a mal url
     *
     * @param string $url
     *
     * @return string
     */
    public static function idFromUrl(string $url): string
    {
        return (int)preg_replace('#https://myanimelist.net(/\w+/)(\d+).*#', '$2', $url);
    }

    /**
     * @param string $date
     *
     * @return \DateTimeImmutable|null
     */
    public static function parseDate(string $date): ?\DateTimeImmutable
    {
        if (preg_match('/^\d{4}$/', $date)) {
            return \DateTimeImmutable::createFromFormat('Y-m-d', $date.'-01-01', new \DateTimeZone('UTC'));
        }
        try {
            return new \DateTimeImmutable($date, new \DateTimeZone('UTC'));
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
}
