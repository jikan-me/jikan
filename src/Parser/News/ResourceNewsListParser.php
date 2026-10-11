<?php

namespace Jikan\Parser\News;

use Jikan\Model\News\ResourceNewsList;
use Jikan\Model\News\ResourceNewsListItem;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ResourceNewsListParser
 *
 * @package Jikan\Parser
 */
class ResourceNewsListParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private Crawler $crawler;

    /**
     * MangaParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return ResourceNewsList
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): ResourceNewsList
    {
        return ResourceNewsList::fromParser($this);
    }

    /**
     * @return ResourceNewsListItem[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getResults(): array
    {
        // The news index currently renders items as div.news-unit (an
        // exact-class match: news-unit-right does not contain "news-unit" as a
        // token). The old js-scrollfix-bottom-rel/.clearfix layout is gone.
        // MAL also injects ad placements inside div.news-unit, so keep only the
        // units that actually carry an article title link.
        return $this->crawler
            ->filterXPath('//div[contains(concat(" ", normalize-space(@class), " "), " news-unit ")]')
            ->reduce(
                static fn(Crawler $unit): bool => $unit->filterXPath('.//p[contains(@class, "title")]/a')->count() > 0
            )
            ->each(
                function (Crawler $crawler) {
                    return new ResourceNewsListItemParser($crawler)->getModel();
                }
            );
    }

    /**
     * @return bool
     */
    public function getHasNextPage(): bool
    {
        // The news index paginates with `?p=N` range links (e.g. "21 - 40");
        // a link to a page beyond 1 means a next page exists.
        $pageLinks = $this->crawler
            ->filterXPath('//*[@id="content"]//a[contains(@href, "?p=")]');

        foreach ($pageLinks as $node) {
            if (preg_match('~[?&]p=(\d+)~', $node->getAttribute('href'), $m) && (int) $m[1] > 1) {
                return true;
            }
        }

        return false;
    }
}
