<?php

namespace Jikan\Parser\Common;

use Jikan\Model\Picture;
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
     */
    public function getLarge(): string
    {
        return $this->crawler->filterXPath('//a')->attr('href');
    }

    /**
     * @return string
     */
    public function getSmall() : string
    {
        return $this->crawler->filterXPath('//img')->attr('src');
    }

    /**
     * @return Picture
     */
    public function getModel(): Picture
    {
        return Picture::fromParser($this);
    }
}
