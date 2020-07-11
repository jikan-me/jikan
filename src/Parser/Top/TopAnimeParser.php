<?php

namespace Jikan\Parser\Top;

use Jikan\Model\Top\TopAnime;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TopAnimeParser
 *
 * @package Jikan\Parser\Top
 */
class TopAnimeParser
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
     * @return TopAnime[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getTopAnime(): array
    {
        return $this->crawler
            ->filterXPath('//tr[@class="ranking-list"]')
            ->each(
                function (Crawler $crawler) {
                    return TopAnime::fromParser(new TopListItemParser($crawler));
                }
            );
    }
}
