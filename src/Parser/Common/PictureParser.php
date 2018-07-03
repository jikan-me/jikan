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
     * @return null|string
     */
    public function getLarge()
    {
        return $this->crawler->filterXPath('//a')->attr('href');
    }

    /**
     * @return null|string
     */
    public function getSmall()
    {
        return $this->crawler->filterXPath('//img')->attr('src');
    }

    /**
     * @return Picture
     */
    public function getModel()
    {
        return Picture::fromParser($this);
    }
}
