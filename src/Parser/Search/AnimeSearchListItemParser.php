<?php

namespace Jikan\Parser\Search;

use Jikan\Helper\Parser;
use Jikan\Helper\JString;
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
        return $this->crawler->filterXPath('//td[2]/a/strong')->text();
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->crawler->filterXPath('//td[1]/div/a/img')->attr('data-src');
    }

    /**
     * @return string
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
     */
    public function getType(): string
    {
        return JString::cleanse(
            $this->crawler->filterXPath('//td[3]')->text()
        );
    }

    /**
     * @return int
     */
    public function getEpisodes(): int
    {
        return (int) $this->crawler->filterXPath('//td[4]')->text();
    }

    /**
     * @return float
     */
    public function getScore(): float
    {
        return (float) $this->crawler->filterXPath('//td[5]')->text();
    }

    /**
     * @return ?\DateTimeImmutable
     */
    public function getStartDate(): ?\DateTimeImmutable
    {
        $date = $this->getStartDateString();

        if (is_null($date)) {
            return null;
        }

        return Parser::parseDateMDY($date);
    }

    /**
     * @return ?\DateTimeImmutable
     */
    public function getEndDate(): ?\DateTimeImmutable
    {
        $date = $this->getEndDateString();

        if (is_null($date)) {
            return null;
        }

        return Parser::parseDateMDY($date);
    }

    /**
     * @return ?string
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
     * @return ?string
     */
    public function getEndDateString(): ?string
    {
        $date = JString::cleanse($this->crawler->filterXPath('//td[7]')->text());

        if ($date === '-') {
            return null;
        }

        return $date;
    }

    /**
     * @return int
     */
    public function getMembers(): int
    {
        return (int) str_replace(
            ',',
            '',
            $this->crawler->filterXPath('//td[8]')->text()
        );
    }

    /**
     * @return ?string
     */
    public function getRated(): ?string
    {
        $rated = JString::cleanse($this->crawler->filterXPath('//td[9]')->text());

        if ($rated === '-') {
            return null;
        }

        return $rated;
    }
}
