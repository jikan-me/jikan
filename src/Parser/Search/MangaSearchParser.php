<?php

namespace Jikan\Parser\Search;

use Symfony\Component\DomCrawler\Crawler;
use Jikan\Model\Search\MangaSearch;

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
     * @return array
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getResults(): array
    {
        return $this->crawler
            ->filterXPath('//div[contains(@class, "js-categories-seasonal")]/table/tr[1]')
            ->nextAll()
            ->each(function (Crawler $c) {
                return (new MangaSearchListItemParser($c))->getModel();
            });
    }

    /**
     * @return int
     */
    public function getLastPage(): int
    {
        return 0;
    }
}
