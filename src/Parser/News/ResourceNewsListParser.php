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
        $units = $this->crawler
            ->filterXPath('//div[contains(concat(" ", normalize-space(@class), " "), " news-unit ")]')
            ->reduce(
                static fn(Crawler $unit): bool => $unit->filterXPath('.//p[contains(@class, "title")]/a')->count() > 0
            );

        if ($units->count()) {
            return $units->each(
                function (Crawler $crawler) {
                    return new ResourceNewsListItemParser($crawler)->getModel();
                }
            );
        }

        // A title's own news page (/anime/{id}/_/news, /manga/{id}/_/news) has no div.news-unit: one block per
        // article, each with a picSurround image. The parent of that image is the article.
        return $this->crawler
            ->filterXPath('//*[@id="content"]//div[contains(@class, "picSurround")]/parent::div')
            ->each(
                function (Crawler $crawler) {
                    return new TitleNewsListItemParser($crawler)->getModel();
                }
            );
    }

    /**
     * @return bool
     */
    public function getHasNextPage(): bool
    {
        return $this->getLastVisiblePage() > $this->currentPage();
    }

    /**
     * The furthest page the page's links reach. The news index links ranges ("21 - 40") up to the last page; a
     * title's own news page links only the next one ("More News"), and its "News" tab links to the page you are
     * on, so only a link beyond the current page counts.
     *
     * @return int
     */
    public function getLastVisiblePage(): int
    {
        $last = $this->currentPage();
        foreach ($this->crawler->filterXPath('//*[@id="content"]//a[contains(@href, "?p=")]') as $node) {
            if ($node instanceof \DOMElement && preg_match('~[?&]p=(\d+)~', $node->getAttribute('href'), $m)) {
                $last = max($last, (int) $m[1]);
            }
        }

        return $last;
    }

    private function currentPage(): int
    {
        return preg_match('~[?&]p=(\d+)~', $this->crawler->getUri() ?? '', $m) ? max(1, (int) $m[1]) : 1;
    }
}
