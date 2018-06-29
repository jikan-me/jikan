<?php

namespace Jikan\Parser\Common;

use Jikan\Model\MalUrl;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MalUrlParser
 *
 * @package Jikan\Parser\Common
 */
class MalUrlParser
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
     * @return MalUrl
     * @throws \InvalidArgumentException
     */
    public function getModel(): MalUrl
    {
        return new MalUrl(
            $this->crawler->text(),
            'https://myanimelist.net'.$this->crawler->attr('href')
        );
    }
}
