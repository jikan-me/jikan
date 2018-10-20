<?php

namespace Jikan\Parser\Search;

use Jikan\Model\Search\PersonSearch;
use Jikan\Model\Search\PersonSearchListItem;
use Jikan\Parser\Person\PersonParser;
use Symfony\Component\DomCrawler\Crawler;

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
     * @throws \Exception
     */
    public function getModel(): PersonSearch
    {
        return PersonSearch::fromParser($this);
    }

    /**
     * @return PersonSearchListItem[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getResults(): array
    {
        $data = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr[1]')
            ->nextAll()
            ->each(
                function (Crawler $c) {
                    return (new PersonSearchListItemParser($c))->getModel();
                }
            );

        // If only a single result is found, the $data array will be empty.
        if (empty($data)) {
            $data = $this->crawler
                ->each(
                    function (Crawler $c) {
                        return PersonSearchListItem::fromPersonParser(new PersonParser($c));
                    }
                );
        }
            
        return $data;
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
