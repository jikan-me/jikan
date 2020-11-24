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
        // if the query is empty, MAL returns a ranking of "Most Favorited" people
        // since that's not the scope of this method, we return empty results
        // most favorited people are returned via the `TopPeople` API method
        if ($this->crawler->filterXPath('//*[@id="content"]/table[@class="people-favorites-ranking-table"]')->count()) {
            return [];
        }

        if ($this->crawler->filterXPath('//div[@id="contentWrapper"]/div[1]/div[@class="h1 edit-info"]')->count()) {
            return $this->crawler
                ->each(
                    function (Crawler $c) {
                        return PersonSearchListItem::fromPersonParser(new PersonParser($c));
                    }
                );
        }

        return $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr')
            ->each(
                function (Crawler $c) {
                    return (new PersonSearchListItemParser($c))->getModel();
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
            ->filterXPath('//div[contains(@class, "normal_header")]/div/div/span');

        if (!$pages->count()) {
            return 1;
        }

        $pages = explode(' ', $pages->text());

        return (int) str_replace(['[', ']'], '', end($pages));
    }

    /**
     * @return bool
     */
    public function getHasNextPage(): bool
    {
        $pages = $this->crawler
            ->filterXPath('//div[contains(@class, "normal_header")]/div/div/span');

        if (!$pages->count()) {
            return false;
        }

        if (preg_match('~\[\d+]\s(\d+)~', $pages->text())) {
            return true;
        }

        return false;
    }
}
