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
        $crawler = clone $crawler;
        $crawler->children()->each(
            function (Crawler $crawler) {
                $node = $crawler->getNode(0);
                /** @noinspection NullPointerExceptionInspection */
                $node->parentNode->removeChild($node);
            }
        );

        return $crawler;
    }
}
