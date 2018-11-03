<?php

namespace Jikan\Parser\Anime;

use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimeRecommendations
 *
 * @package Jikan\Parser\Anime
 */
class AnimeRecommendations implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * AnimeRecommendations constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getModel(): array
    {
        return $this->crawler
            ->filterXPath('//div[@class="js-scrollfix-bottom-rel"]/div[@class="borderClass"]')
            ->each(function ($c) {
                return (new AnimeRecommendation($c))->getModel();
            });
    }
}
