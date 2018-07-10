<?php

namespace Jikan\Parser\Search;

use Symfony\Component\DomCrawler\Crawler;
use Jikan\Model\Search\PersonSearch;

/**
 * Class PersonSearchParser
 *
 * @package Jikan\Parser
 */
class PersonSearchParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * PersonSearchParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return PersonSearch
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): PersonSearch
    {
        return PersonSearch::fromParser($this);
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
                return (new PersonSearchListItemParser($c))->getModel();
            });
    }

    /**
     * @return int
     */
    public function getLastPage(): int
    {
        $pages = $this->crawler
            ->filterXPath('//div[contains(@class, "normal_header")]/div/div/span/a');

        if (!$pages->count()) {
            return 1;
        }

        return (int) $pages->last()->text();
    }
}
