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
        $probrem = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[1][contains(text(), "There were some probrems")]');

        if ($probrem->count()) {
            return [];
        }

        return $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr')
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
            ->filterXPath('//div[@id="content"]/div[@class="borderClass"][1]/div/span');

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
