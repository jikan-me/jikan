<?php

namespace Jikan\Parser\Common;

use Jikan\Helper\JString;
use Jikan\Model\Common\Url;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class UrlParser
 *
 * @package Jikan\Parser\Common
 */
class UrlParser
{
    /**
     * @var Crawler
     */
    private Crawler $crawler;

    /**
     * MalUrlParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return Url
     * @throws \InvalidArgumentException
     */
    public function getModel(): Url
    {
        return new Url(
            JString::cleanse($this->crawler->text()),
            JString::cleanse($this->crawler->attr('href'))
        );
    }
}
