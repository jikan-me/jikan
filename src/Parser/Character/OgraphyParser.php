<?php

namespace Jikan\Parser\Character;

use Jikan\Helper\Parser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class OgraphyParser
 *
 * @package Jikan\Parser
 */
abstract class OgraphyParser
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
     * @throws \InvalidArgumentException
     */
    public function getMalId(): int
    {
        return (int)preg_replace('#https://myanimelist.net/\w+/(\d+).*#', '$1', $this->getUrl());
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return $this->crawler->filterXPath('//td/a')->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getName(): string
    {
        return $this->crawler->filterXPath('//td/a')->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getImage(): string
    {
        return Parser::parseImageQuality(
            $this->crawler->filterXPath('//img')->attr('data-src')
        );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getRole(): string
    {
        return $this->crawler->filterXPath('//small')->last()->text();
    }
}
