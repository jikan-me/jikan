<?php

namespace Jikan\Parser\Top;

use Jikan\Model\Top\TopManga;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TopMangaParser
 *
 * @package Jikan\Parser\Top
 */
class TopMangaParser
{
    /**
     * @var Crawler
     */
    private $crawler;


    /**
     * CharacterListItemParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return TopManga[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getTopManga(): array
    {
        return $this->crawler
            ->filterXPath('//tr[@class="ranking-list"]')
            ->each(
                function (Crawler $crawler) {
                    return TopManga::fromParser(new TopListItemParser($crawler));
                }
            );
    }
}
