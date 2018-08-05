<?php

namespace Jikan\Parser\Common;

use Jikan\Model\Common\MalUrl;
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
        $href = $this->crawler->attr('href');
        $href = str_replace('https://myanimelist.net', '', $href);

        return new MalUrl(
            $this->crawler->text(),
            'https://myanimelist.net'.$href
        );
    }
}
