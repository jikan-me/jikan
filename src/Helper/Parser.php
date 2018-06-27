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
        $crawler->children()->each(
            function (Crawler $crawler) {
                $node = $crawler->getNode(0);
                /** @noinspection NullPointerExceptionInspection */
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
}
