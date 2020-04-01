<?php

namespace Jikan\Parser\User\Profile;

use Jikan\Helper\Parser;
use Jikan\Model\User\MangaStats;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MangaStats
 *
 * @package Jikan\Parser
 */
class MangaStatsParser
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
     * @return MangaStats
     * @throws \InvalidArgumentException
     */
    public function getModel(): MangaStats
    {
        return MangaStats::fromParser($this);
    }

    /**
     * @return float|null
     * @throws \InvalidArgumentException
     */
    public function getDaysRead(): ?float
    {
        $node = $this->crawler
            ->filterXPath('//div[@class=\'di-tc al pl8 fs12 fw-b\'][2]');

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
                '//*[@id="statistics"]/div[contains(@class, "user-statistics-stats")][2]
                /div[contains(@class, "stats manga")]/div[1]/div[2]/span[contains(@class, "score-label")]'
            );

        if ($node->count()) {
            return (float) $node->text();
        }

        $node = $this->crawler
            ->filterXPath('//div[@class=\'di-tc ar pr8 fs12 fw-b\'][2]');

        if (!$node->count()) {
            return null;
        }

        return Parser::removeChildNodes($node)->text();
    }


    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getReading(): ?int
    {
        $node = $this->crawler
            ->filterXPath('//a[contains(@class, \'reading\')]/following-sibling::span');

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
            ->filterXPath('//a[contains(@class, \'completed\')][2]/following-sibling::span');

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
            ->filterXPath('//a[contains(@class, \'on_hold\')][2]/following-sibling::span');

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
            ->filterXPath('//a[contains(@class, \'dropped\')][2]/following-sibling::span');

        if (!$node->count()) {
            return null;
        }

        return str_replace(',', '', $node->text());
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getPlanToRead(): ?int
    {
        $node = $this->crawler
            ->filterXPath('//a[contains(@class, \'plan_to_read\')]/following-sibling::span');

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
            ->filterXPath('//ul[@class=\'stats-data fl-r\'][2]/li[1]/span[2]');

        if (!$node->count()) {
            return null;
        }

        return str_replace(',', '', $node->text());
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getReread(): ?int
    {
        $node = $this->crawler
            ->filterXPath('//ul[@class=\'stats-data fl-r\'][2]/li[2]/span[2]');

        if (!$node->count()) {
            return null;
        }

        return str_replace(',', '', $node->text());
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getChaptersRead(): ?int
    {
        $node = $this->crawler
            ->filterXPath('//ul[@class=\'stats-data fl-r\'][2]/li[3]/span[2]');

        if (!$node->count()) {
            return null;
        }

        return str_replace(',', '', $node->text());
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getVolumesRead(): ?int
    {
        $node = $this->crawler
            ->filterXPath('//ul[@class=\'stats-data fl-r\'][2]/li[4]/span[2]');

        if (!$node->count()) {
            return null;
        }

        return str_replace(',', '', $node->text());
    }
}
