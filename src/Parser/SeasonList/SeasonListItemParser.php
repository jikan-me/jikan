<?php

namespace Jikan\Parser\SeasonList;

use Jikan\Model\SeasonList\SeasonListItem;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;
use Jikan\Helper\Constants;

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
     */
    public function getYear(): int
    {
        return (int)preg_replace(
            '/[^0-9]/',
            '',
            $this->crawler
                ->filterXPath('//td')
                ->first()
                ->text()
        );
    }

    /**
     * @return array
     */
    public function getSeasons(): array
    {
        $seasons = $this->crawler->text();

        return array_filter(Constants::SEASONS, function ($season) use ($seasons) {
            return preg_match("/$season/", $seasons) != false;
        });
    }
}
