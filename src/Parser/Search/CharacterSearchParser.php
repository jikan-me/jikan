<?php

namespace Jikan\Parser\Search;

use Jikan\Model\Search\CharacterSearch;
use Jikan\Model\Search\CharacterSearchListItem;
use Symfony\Component\DomCrawler\Crawler;

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
     * @return CharacterSearchListItem[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getResults(): array
    {

        if ($this->crawler
                ->filterXPath('//div[@id="content"]/table/tr[2]/td')
                // Yes, MAL actually has "probrems"
                ->text() === 'There were some probrems:Must have at least 3 byte characters to search'
        ) {
            return [];
        }

        return $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr[1]')
            ->nextAll()
            ->each(
                function (Crawler $c) {
                    return (new CharacterSearchListItemParser($c))->getModel();
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
            ->filterXPath('//div[@id="content"]/div[@class="borderClass"][1]/div/span/a');

        if (!$pages->count()) {
            return 1;
        }

        return (int)$pages->last()->text();
    }
}
