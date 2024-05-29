<?php

namespace Jikan\Parser\Common;

use Jikan\Helper\Parser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ItemMetaParser
 *
 * @package Jikan\Parser
 */
abstract class ItemMetaParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * ItemMetaParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getMalId(): ?int
    {
        return (int)preg_replace('#https://myanimelist.net/\w+/(\d+).*#', '$1', $this->getUrl());
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getUrl(): ?string
    {
        return $this->crawler->filterXPath('//a')->attr('href');
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getName(): ?string
    {
        return $this->crawler->filterXPath('//a')->text();
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getImage(): ?string
    {
        return Parser::parseImageQuality(
            $this->crawler->filterXPath('//img')->attr('data-src')
        );
    }
}
