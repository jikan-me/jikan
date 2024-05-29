<?php

namespace Jikan\Parser\Watch;

use Jikan\Model;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class WatchPromotionalVideosParser
 *
 * @package Jikan\Parser
 */
class WatchPromotionalVideosParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * WatchPromotionalVideosParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    public function getModel(): Model\Watch\PromotionalVideos
    {
        return Model\Watch\PromotionalVideos::fromParser($this);
    }

    public function getResults() : array
    {
        $node = $this->crawler->filterXPath(
            '//*[@id="content"]/div[3]/div/div[contains(@class, "video-list-outer-vertical")]'
        );

        if (!$node->count()) {
            return [];
        }

        return $node->each(function (Crawler $crawler) {
            return Model\Watch\PromotionalVideoListItem::fromParser(new PromotionalVideoListItemParser($crawler));
        });
    }

    public function getHasNextPage() : bool
    {
        $node = $this->crawler->filterXPath(
            '//*[@id="content"]/div[contains(@class, "pagination")]/a[contains(text(), "More")]'
        );

        if ($node->count()) {
            return true;
        }

        return false;
    }

    public function getLastVisiblePage() : int
    {
        $node = $this->crawler->filterXPath(
            '//*[@id="content"]/div[contains(@class, "pagination")]/span[@class="link-blue-box"]'
        );

        if (!$node->count()) {
            return 1;
        }

        $currentPage = (int) $node->text();

        if (!$this->getHasNextpage()) {
            return $currentPage;
        }

        return $currentPage + 1;
    }
}
