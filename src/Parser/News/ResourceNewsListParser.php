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
        return $this->crawler
            ->filterXPath('//div[contains(@class,"js-scrollfix-bottom-rel")]/div[@class="clearfix"]')
            ->each(
                function (Crawler $crawler) {
                    return (new ResourceNewsListItemParser($crawler))->getModel();
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
