<?php

namespace Jikan\Parser\Common;

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
    private $crawler;

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
            $this->crawler->text(),
            $this->crawler->attr('href')
        );
    }
}
