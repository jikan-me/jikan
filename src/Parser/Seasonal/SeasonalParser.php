<?php

namespace Jikan\Parser\Seasonal;

use Jikan\Helper\JString;
use Jikan\Model;
use Jikan\Parser\Common\AnimeCardParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class SeasonalParser
 *
 * @package Jikan\Parser
 */
class SeasonalParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * SeasonalParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return Model\Seasonal
     */
    public function getModel(): Model\Seasonal
    {
        return Model\Seasonal::fromParser($this);
    }

    /**
     * @return array|Model\SeasonalAnime[]
     * @throws \RuntimeException
     */
    public function getSeasonalAnime(): array
    {
        return $this->crawler
            ->filter('div.seasonal-anime')
            ->each(
                function (Crawler $animeCrawler) {
                    return (new AnimeCardParser($animeCrawler))->getModel();
                }
            );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getSeason(): string
    {
        $season = $this->crawler->filter('div.navi-seasonal a.on')->text();

        return JString::cleanse($season);
    }
}
