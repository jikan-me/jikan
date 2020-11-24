<?php

namespace Jikan\Parser\SeasonList;

use Jikan\Helper\Constants;
use Jikan\Model\SeasonList\SeasonListItem;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class SeasonListItemParser
 *
 * @package Jikan\Parser\SeasonList
 */
class SeasonListItemParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * SeasonListItemParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return SeasonListItem
     * @throws \InvalidArgumentException
     */
    public function getModel(): SeasonListItem
    {
        return SeasonListItem::fromParser($this);
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getYear(): int
    {
        return (int)preg_replace(
            '/\D/',
            '',
            $this->crawler
                ->filterXPath('//td')
                ->first()
                ->text()
        );
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getSeasons(): array
    {
        $seasons = $this->crawler->text();

        $seasons = array_filter(
            Constants::SEASONS,
            function ($season) use ($seasons) {
                return preg_match("/$season/", $seasons);
            }
        );

        return array_map('strtolower', $seasons);
    }
}
