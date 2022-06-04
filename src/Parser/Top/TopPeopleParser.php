<?php

namespace Jikan\Parser\Top;

use Jikan\Model\Top\TopPeople;
use Jikan\Model\Top\TopPersonListItem;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TopPeoplesParser
 *
 * @package Jikan\Parser\Top
 */
class TopPeopleParser
{
    /**
     * @var Crawler
     */
    private $crawler;


    /**
     * CharacterListItemParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return TopPeople
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): TopPeople
    {
        return TopPeople::fromParser($this);
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->crawler
            ->filterXPath('//tr[@class="ranking-list"]')
            ->each(
                function (Crawler $crawler) {
                    return TopPersonListItem::fromParser(new TopListItemParser($crawler));
                }
            );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getLastPage(): int
    {
        $pages = $this->crawler
            ->filterXPath('//*[@id="content"]/h2/div/span/a[contains(@class, "next")]');

        if (!$pages->count()) {
            return 1;
        }

        preg_match('~\?limit=(\d+)$~', $pages->attr('href'), $page);
        $page = (int) $page[1] / 50;

        return $page + 1;
    }

    /**
     * @return bool
     */
    public function getHasNextPage(): bool
    {
        $pages = $this->crawler
            ->filterXPath('//*[@id="content"]/h2/div/span/a[contains(@class, "next")]');


        if ($pages->count()) {
            return true;
        }

        return false;
    }
}
