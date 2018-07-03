<?php

namespace Jikan\Parser\Anime;

use Jikan\Helper\Parser;
use Jikan\Model\EpisodeListItem;
use Jikan\Model\VoiceActor;
use Jikan\Parser\Episode\VoiceActorParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class EpisodeListItemParser
 *
 * @package Jikan\Parser\Episode
 */
class EpisodeListItemParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * EpisodeListItemParser constructor.
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
    public function getMalId(): int
    {
        return Parser::idFromUrl($this->getEpisodeUrl());
    }

    /**
     * @return string
     */
    public function getEpisodeUrl(): string
    {
        return $this->crawler->filterXPath('//td[2]/a')->attr('href');
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->crawler->filterXPath('//td[2]/a')->text();
    }

    /**
     * @return string
     */
    public function getTitleJapanese(): string
    {
        return $this->crawler->filterXPath('//td[2]/a')->text();
    }

    /**
     * @return string
     */
    public function getTitleRomanji(): string
    {
        return $this->crawler->filterXPath('//td[2]/a')->text();
    }

    /**
     * @return string
     */
    public function getAired(): string
    {
        return $this->crawler->filterXPath('//td[2]/a')->text();
    }

    /**
     * @return bool
     */
    public function getFiller(): bool
    {
        return $this->crawler->filterXPath('//td[2]/a')->text();
    }

    /**
     * @return bool
     */
    public function getRecap(): bool
    {
        return $this->crawler->filterXPath('//td[2]/a')->text();
    }

    /**
     * @return string
     */
    public function getVideoUrl(): string
    {
        return $this->crawler->filterXPath('//td[2]/a')->text();
    }

    /**
     * @return string
     */
    public function getForumUrl(): string
    {
        return $this->crawler->filterXPath('//td[2]/a')->text();
    }
}
