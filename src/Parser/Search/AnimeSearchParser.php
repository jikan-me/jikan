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
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): AnimeSearch
    {
        return AnimeSearch::fromParser($this);
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return [];
    }

    /**
     * @return int
     */
    public function getLastPage(): int
    {
        return 0;
    }
}
