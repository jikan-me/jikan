<?php

namespace Jikan\Parser\Search;

use Symfony\Component\DomCrawler\Crawler;
use Jikan\Model\Search\AnimeSearch;

/**
 * Class AnimeSearchParser
 *
 * @package Jikan\Parser
 */
class AnimeSearchParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * AnimeSearchParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return AnimeSearch
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): AnimeSearch
    {
        return AnimeSearch::fromParser($this);
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
                return (new AnimeSearchListItemParser($c))->getModel();
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
