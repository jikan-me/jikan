<?php

namespace Jikan\Parser\Top;

use Jikan\Model\Top\TopAnime;
use Jikan\Model\Top\TopAnimeListItem;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TopAnimeParser
 *
 * @package Jikan\Parser\Top
 */
class TopAnimeParser
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
     * @return TopAnime
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): TopAnime
    {
        return TopAnime::fromParser($this);
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
                    return TopAnimeListItem::fromParser(new TopListItemParser($crawler));
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
