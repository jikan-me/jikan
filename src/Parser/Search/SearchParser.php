<?php

namespace Jikan\Parser\Search;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Class SearchParser
 *
 * @package Jikan\Parser
 */
class SearchParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * @var string[]
     */
    private $keys = [];

    /**
     * SeasonalParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * Get the keys of the extra data
     *
     * @return array
     */
    public function getExtraData(): array
    {
        if (\count($this->keys)) {
            return $this->keys;
        }
        $keys = $this->crawler
            ->filterXPath('//div[contains(@class, "js-categories-seasonal")]/table/tr[1]/td')
            ->each(
                function (Crawler $c) {
                    return $c->text();
                }
            );

        return $this->keys = array_splice($keys, 2);
    }
}
