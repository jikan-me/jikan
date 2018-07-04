<?php

namespace Jikan\Parser\Search;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Class SearchParser
 *
 * @package Jikan\Parser
 */
class SearchItemParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * @var string[]
     */
    private $keys;

    /**
     * SeasonalParser constructor.
     *
     * @param Crawler $crawler
     * @param array   $keys
     */
    public function __construct(Crawler $crawler, array $keys)
    {
        $this->crawler = $crawler;
        $this->keys = $keys;
    }
}
