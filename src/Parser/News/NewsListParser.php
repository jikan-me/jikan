<?php

namespace Jikan\Parser\News;

use Jikan\Model\News\NewsList;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class NewsListParser
 *
 * @package Jikan\Parser
 */
class NewsListParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private Crawler $crawler;

    /**
     * NewsListParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }


    public function getModel(): NewsList
    {
        return NewsList::fromParser($this);
    }


    public function getResults(): array
    {
        return $this->crawler
            ->filterXPath('//*[@id="content"]/div[1]/div/div[contains(@class, "news-list")]/div[contains(@class, "news-unit") and contains(@class, "rect")]')
            ->each(
                function (Crawler $crawler) {
                    return (new NewsListItemParser($crawler))->getModel();
                }
            );
    }

    /**
     * @return bool
     */
    public function getHasNextPage(): bool
    {
        return false;
    }

    /**
     * @return int
     */
    public function getLastVisiblePage(): int
    {
        return 1;
    }
}
