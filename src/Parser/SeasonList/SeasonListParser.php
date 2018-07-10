<?php

namespace Jikan\Parser\SeasonList;

use Jikan\Model\SeasonList\SeasonListItem;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class SeasonListParser
 *
 * @package Jikan\Parser\SeasonList
 */
class SeasonListParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * SeasonListParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return SeasonListItem[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): array
    {
        return $this->crawler
            ->filterXPath('//table[contains(@class, "anime-seasonal-byseason")]//tr')
            ->each(
                function (Crawler $crawler) {
                    return (new SeasonListItemParser($crawler))->getModel();
                }
            );
    }
}
