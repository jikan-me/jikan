<?php

namespace Jikan\Parser\Common;

use Jikan\Model\Common\Picture;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class PictureParser
 *
 * @package Jikan\Parser\Common
 */
class PictureParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * PictureParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getLarge(): string
    {
        return $this->crawler->filterXPath('//a')->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getSmall(): string
    {
        return $this->crawler->filterXPath('//img')->attr('data-src');
    }

    /**
     * @return Picture
     * @throws \InvalidArgumentException
     */
    public function getModel(): Picture
    {
        return Picture::fromParser($this);
    }
}
