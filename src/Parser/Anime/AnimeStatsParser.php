<?php

namespace Jikan\Parser\Anime;

use Jikan\Model\Anime\AnimeStats;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimeStatsParser
 *
 * @package Jikan\Parser\Common
 */
class AnimeStatsParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * AnimeParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return int
     */
    public function getWatching(): int
    {
        return $this->sanitize($this->crawler
            ->filterXPath('//div[@class="spaceit_pad"]/span[contains(text(), \'Watching:\')]')
            ->parents()
            ->getNode(0)->textContent);
    }

    /**
     * @return int
     */
    public function getCompleted(): int
    {
        return $this->sanitize($this->crawler
            ->filterXPath('//div[@class="spaceit_pad"]/span[contains(text(), \'Completed:\')]')
            ->parents()
            ->getNode(0)->textContent);
    }

    /**
     * @return int
     */
    public function getOnHold(): int
    {
        return $this->sanitize($this->crawler
            ->filterXPath('//div[@class="spaceit_pad"]/span[contains(text(), \'On-Hold:\')]')
            ->parents()
            ->getNode(0)->textContent);
    }

    /**
     * @return int
     */
    public function getDropped(): int
    {
        return $this->sanitize($this->crawler
            ->filterXPath('//div[@class="spaceit_pad"]/span[contains(text(), \'Dropped:\')]')
            ->parents()
            ->getNode(0)->textContent);
    }

    /**
     * @return int
     */
    public function getPlanToWatch(): int
    {
        return $this->sanitize($this->crawler
            ->filterXPath('//div[@class="spaceit_pad"]/span[contains(text(), \'Plan to Watch:\')]')
            ->parents()
            ->getNode(0)->textContent);
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->sanitize($this->crawler
            ->filterXPath('//div[@class="spaceit_pad"]/span[contains(text(), \'Total:\')]')
            ->parents()
            ->getNode(0)->textContent);
    }

    /**
     * @param $input
     * @param $badText
     *
     * @return float
     */
    private function sanitize($input): int
    {
        return (int)preg_replace('/\D/', '', $input);
    }

    /**
     * @return AnimeStats
     * @throws \InvalidArgumentException
     */
    public function getModel(): AnimeStats
    {
        return AnimeStats::fromParser($this);
    }
}
