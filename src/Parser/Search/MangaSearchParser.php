<?php

namespace Jikan\Parser\Search;

use Jikan\Model\Search\MangaSearch;
use Jikan\Model\Search\MangaSearchListItem;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MangaSearchParser
 *
 * @package Jikan\Parser
 */
class MangaSearchParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * MangaSearchParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return MangaSearch
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): MangaSearch
    {
        return MangaSearch::fromParser($this);
    }

    /**
     * @return MangaSearchListItem[]
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getResults(): array
    {
        $results = $this->crawler
            ->filterXPath('//div[contains(@class, "js-categories-seasonal")]/table/tr[1]');

        if (!$results->count()) {
            return [];
        }

        return $results->nextAll()
            ->each(
                function (Crawler $c) {
                    return (new MangaSearchListItemParser($c))->getModel();
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
            ->filterXPath('//div[contains(@class, "normal_header")]/div/div/span/a');

        if (!$pages->count()) {
            return 1;
        }

        return (int)$pages->last()->text();
    }
}
