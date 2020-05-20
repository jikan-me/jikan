<?php

namespace Jikan\Parser\User\Profile;

use Jikan\Helper\Parser;
use Jikan\Model\User\AnimeStats;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimeStats
 *
 * @package Jikan\Parser
 */
class AnimeStatsParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * Animeograpy constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return \Jikan\Model\User\AnimeStats
     * @throws \InvalidArgumentException
     */
    public function getModel(): AnimeStats
    {
        return AnimeStats::fromParser($this);
    }

    /**
     * @return float|null
     * @throws \InvalidArgumentException
     */
    public function getDaysWatched(): ?float
    {
        $node = $this->crawler
            ->filterXPath('//div[@class=\'di-tc al pl8 fs12 fw-b\'][1]');

        if (!$node->count()) {
            return null;
        }

        return str_replace(
            ',',
            '',
            Parser::removeChildNodes($node)->text()
        );
    }

    /**
     * @return float|null
     * @throws \InvalidArgumentException
     */
    public function getMeanScore(): ?float
    {
        $node = $this->crawler
            ->filterXPath(
                '//*[@id="statistics"]/div[contains(@class, "user-statistics-stats")][1]
                /div[contains(@class, "stats anime")]/div[1]/div[2]/span[contains(@class, "score-label")]'
            );

        if ($node->count()) {
            return (float) $node->text();
        }

        $node = $this->crawler
            ->filterXPath('//div[@class=\'di-tc ar pr8 fs12 fw-b\'][1]');

        if (!$node->count()) {
            return null;
        }

        return Parser::removeChildNodes($node)->text();
    }


    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getWatching(): ?int
    {
        $node = $this->crawler
            ->filterXPath('//a[contains(@class, \'watching\')]/following-sibling::span');

        if (!$node->count()) {
            return null;
        }

        return str_replace(',', '', $node->text());
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getCompleted(): ?int
    {
        $node = $this->crawler
            ->filterXPath('//a[contains(@class, \'completed\')]/following-sibling::span');

        if (!$node->count()) {
            return null;
        }

        return str_replace(',', '', $node->text());
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getOnHold(): ?int
    {
        $node = $this->crawler
            ->filterXPath('//a[contains(@class, \'on_hold\')]/following-sibling::span');

        if (!$node->count()) {
            return null;
        }

        return str_replace(',', '', $node->text());
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getDropped(): ?int
    {
        $node = $this->crawler
            ->filterXPath('//a[contains(@class, \'dropped\')]/following-sibling::span');

        if (!$node->count()) {
            return null;
        }

        return str_replace(',', '', $node->text());
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getPlanToWatch(): ?int
    {
        $node = $this->crawler
            ->filterXPath('//a[contains(@class, \'plan_to_watch\')]/following-sibling::span');

        if (!$node->count()) {
            return null;
        }

        return str_replace(',', '', $node->text());
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getTotalEntries(): ?int
    {
        $node = $this->crawler
            ->filterXPath('//ul[@class=\'stats-data fl-r\'][1]/li[1]/span[2]');

        if (!$node->count()) {
            return null;
        }

        return str_replace(',', '', $node->text());
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getRewatched(): ?int
    {
        $node = $this->crawler
            ->filterXPath('//ul[@class=\'stats-data fl-r\'][1]/li[2]/span[2]');

        if (!$node->count()) {
            return null;
        }

        return str_replace(',', '', $node->text());
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getEpisodesWatched(): ?int
    {
        $node = $this->crawler
            ->filterXPath('//ul[@class=\'stats-data fl-r\'][1]/li[3]/span[2]');

        if (!$node->count()) {
            return null;
        }

        return str_replace(',', '', $node->text());
    }
}
