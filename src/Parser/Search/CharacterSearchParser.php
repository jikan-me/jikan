<?php

namespace Jikan\Parser\Search;

use Symfony\Component\DomCrawler\Crawler;
use Jikan\Model\Search\CharacterSearch;

/**
 * Class CharacterSearchParser
 *
 * @package Jikan\Parser
 */
class CharacterSearchParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * CharacterSearchParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return CharacterSearch
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): CharacterSearch
    {
        return CharacterSearch::fromParser($this);
    }

    /**
     * @return array
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getResults(): array
    {
        return $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr[1]')
            ->nextAll()
            ->each(function (Crawler $c) {
                return (new CharacterSearchListItemParser($c))->getModel();
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
