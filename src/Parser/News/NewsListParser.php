<?php

namespace Jikan\Parser\News;

use Jikan\Model\News\NewsList;
use Jikan\Model\News\NewsListItem;
use Jikan\Model\Search\AnimeSearch;
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
    private $crawler;

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
     * @return NewsList
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): NewsList
    {
        return NewsList::fromParser($this);
    }

    /**
     * @return NewsListItem[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getResults(): array
    {
        return $this->crawler
            ->filterXPath('//div[contains(@class,"js-scrollfix-bottom-rel")]/div[@class="clearfix"]')
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
        $pages = $this->crawler
            ->filterXPath('//*[@id="content"]/table/tr/td[2]/div[1]/a[contains(text(), "More News")]');

        if ($pages->count()) {
            return true;
        }

        return false;
    }
}
