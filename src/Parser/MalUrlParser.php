<?php

namespace Jikan\Parser;

use Jikan\Model\MalUrl;
use Symfony\Component\DomCrawler\Crawler;

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
     */
    public function getModel(): MalUrl
    {
        return new MalUrl(
            $this->crawler->text(),
            'https://myanimelist.net'.$this->crawler->attr('href')
        );
    }
}
