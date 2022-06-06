<?php

namespace Jikan\Parser\Search;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Search\AnimeSearchListItem;
use Symfony\Component\DomCrawler\Crawler;

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
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): AnimeSearchListItem
    {
        return AnimeSearchListItem::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return $this->crawler->filterXPath('//td[2]//a')->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getTitle(): string
    {
        return $this->crawler->filterXPath('//td[2]//a/strong')->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getImageUrl(): string
    {
        return Parser::parseImageQuality(
            $this->crawler->filterXPath('//td[1]/div/a/img')->attr('data-src')
        );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getSynopsis(): string
    {
        return JString::cleanse(
            Parser::removeChildNodes(
                $this->crawler->filterXPath('//td[2]/div[@class="pt4"]')
            )->text()
        );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getType(): string
    {
        return JString::cleanse(
            $this->crawler->filterXPath('//td[3]')->text()
        );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getEpisodes(): int
    {
        return (int)$this->crawler->filterXPath('//td[4]')->text();
    }

    /**
     * @return float
     * @throws \InvalidArgumentException
     */
    public function getScore(): float
    {
        return (float)$this->crawler->filterXPath('//td[5]')->text();
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getMembers(): int
    {
        return (int)str_replace(
            ',',
            '',
            $this->crawler->filterXPath('//td[8]')->text()
        );
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getRated(): ?string
    {
        $rated = JString::cleanse($this->crawler->filterXPath('//td[9]')->text());

        if ($rated === '-') {
            return null;
        }

        return $rated;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isAiring(): bool
    {
        // an entry with one episode can't be airing
        // its either finished airing or hasn't aired yet
        if (1 === $this->getEpisodes()) {
            return false;
        }
        // Start not yet known
        if (null === $this->getStartDate()) {
            return false;
        }
        // Airing no end date
        if (null === $this->getEndDate()) {
            return true;
        }
        $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        // Not yet started
        if ($this->getStartDate() > $now) {
            return false;
        }
        // Already ended
        if ($this->getEndDate() < $now) {
            return false;
        }

        return true;
    }

    /**
     * @return \DateTimeImmutable|null
     * @throws \InvalidArgumentException
     */
    public function getStartDate(): ?\DateTimeImmutable
    {
        $date = $this->getStartDateString();

        if (null === $date) {
            return null;
        }

        return Parser::parseDateMDY($date);
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getStartDateString(): ?string
    {
        $date = JString::cleanse($this->crawler->filterXPath('//td[6]')->text());

        if ($date === '-') {
            return null;
        }

        return $date;
    }

    /**
     * @return \DateTimeImmutable|null
     * @throws \InvalidArgumentException
     */
    public function getEndDate(): ?\DateTimeImmutable
    {
        $date = $this->getEndDateString();

        if (null === $date) {
            return null;
        }

        return Parser::parseDateMDY($date);
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getEndDateString(): ?string
    {
        $date = JString::cleanse($this->crawler->filterXPath('//td[7]')->text());

        if ($date === '-') {
            return null;
        }

        return $date;
    }
}
