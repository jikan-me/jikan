<?php

namespace Jikan\Parser\Search;

use Jikan\Model\DateRange;
use Jikan\Model\MalUrl;
use Symfony\Component\DomCrawler\Crawler;
use Jikan\Model\Search\AnimeSearchListItem;

/**
 * Class AnimeSearchListItemParser
 *
 * @package Jikan\Parser
 */
class AnimeSearchListItemParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * AnimeSearchParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return AnimeSearchListItem
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): AnimeSearchListItem
    {
        return AnimeSearchListItem::fromParser($this);
    }

    /**
     * @return MalUrl
     */
    public function getUrl(): MalUrl
    {
        return new MalUrl($this->getTitle(), "https://myanimelist.net/anime/1/whitebox");
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return "";
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return "";
    }

    /**
     * @return string
     */
    public function getSynopsis(): string
    {
        return "";
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return "";
    }

    /**
     * @return int
     */
    public function getEpisodes(): int
    {
        return 0;
    }

    /**
     * @return float
     */
    public function getScore(): float
    {
        return 0.00;
    }

    /**
     * @return ?DateRange
     */
    public function getStartDate(): ?DateRange
    {
        return new DateRange("04-08-12");
    }

    /**
     * @return ?DateRange
     */
    public function getEndDate(): ?DateRange
    {
        return new DateRange("06-24-12");
    }

    /**
     * @return int
     */
    public function getMembers(): int
    {
        return 0;
    }

    /**
     * @return string
     */
    public function getRated(): string
    {
        return "PG-13";
    }
}
