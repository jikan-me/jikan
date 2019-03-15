<?php

namespace Jikan\Parser\Search;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Search\MangaSearchListItem;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MangaSearchListItemParser
 *
 * @package Jikan\Parser
 */
class MangaSearchListItemParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * MangaSearchParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return MangaSearchListItem
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): MangaSearchListItem
    {
        return MangaSearchListItem::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return $this->crawler->filterXPath('//td[2]/a')->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getTitle(): string
    {
        return $this->crawler->filterXPath('//td[2]/a/strong')->text();
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
    public function getVolumes(): int
    {
        return (int)$this->crawler->filterXPath('//td[4]')->text();
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getChapters(): int
    {
        return (int)$this->crawler->filterXPath('//td[5]')->text();
    }

    /**
     * @return float
     * @throws \InvalidArgumentException
     */
    public function getScore(): float
    {
        return (float)$this->crawler->filterXPath('//td[6]')->text();
    }

    /**
     * @return \DateTimeImmutable|null ?\DateTimeImmutable
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
     * @return null|string ?string
     * @throws \InvalidArgumentException
     */
    public function getStartDateString(): ?string
    {
        $date = JString::cleanse($this->crawler->filterXPath('//td[7]')->text());

        if ($date === '-') {
            return null;
        }

        return $date;
    }

    /**
     * @return \DateTimeImmutable|null ?\DateTimeImmutable
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
     * @return null|string ?string
     * @throws \InvalidArgumentException
     */
    public function getEndDateString(): ?string
    {
        $date = JString::cleanse($this->crawler->filterXPath('//td[8]')->text());

        if ($date === '-') {
            return null;
        }

        return $date;
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
            $this->crawler->filterXPath('//td[9]')->text()
        );
    }
}
