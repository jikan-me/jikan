<?php

namespace Jikan\Parser\News;

use Jikan\Model\News\NewsListItem;
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
     * @return NewsListItem[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): array
    {
        return $this->crawler
            ->filterXPath('//div[@class="js-scrollfix-bottom-rel"]/div[@class="clearfix"]')
            ->each(
                function (Crawler $crawler) {
                    return (new NewsListItemParser($crawler))->getModel();
                }
            );
    }
}
