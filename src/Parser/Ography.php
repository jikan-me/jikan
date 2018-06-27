<?php

namespace Jikan\Parser;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Ography
 *
 * @package Jikan\Parser
 */
abstract class Ography
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
     * @return int
     */
    public function getMalId(): int
    {
        return (int)preg_replace('#https://myanimelist.net/\w+/(\d+).*#', '$1', $this->getUrl());
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->crawler->filterXPath('//td/a')->attr('href');
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->crawler->filterXPath('//td/a')->text();
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->crawler->filterXPath('//img')->attr('src');
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->crawler->filterXPath('//small')->last()->text();
    }
}
