<?php

namespace Jikan\Parser\Top;

use Jikan\Model\Top\TopManga;
use Jikan\Model\Top\TopMangaListItem;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TopMangaParser
 *
 * @package Jikan\Parser\Top
 */
class TopMangaParser
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
     * @return TopManga
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): TopManga
    {
        return TopManga::fromParser($this);
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
                    return TopMangaListItem::fromParser(new TopListItemParser($crawler));
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
            ->filterXPath('//*[@id="content"]/div[4]/h2/span[1]/a[contains(@class, "next")]');

        if (!$pages->count()) {
            return 1;
        }

        $page = ((int) preg_replace('~\?limit=(\d+)~', '$1', $pages->attr('href'))) / 50;

        return $page + 1;
    }

    /**
     * @return bool
     */
    public function getHasNextPage(): bool
    {
        $pages = $this->crawler
            ->filterXPath('//*[@id="content"]/div[4]/h2/span[1]/a[contains(@class, "next")]');


        if ($pages->count()) {
            return true;
        }

        return false;
    }
}
