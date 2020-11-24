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
     * @return \Jikan\Model\Seasonal\Seasonal
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Seasonal\Seasonal
    {
        return Model\Seasonal\Seasonal::fromParser($this);
    }

    /**
     * @return array|\Jikan\Model\Seasonal\SeasonalAnime[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getSeasonalAnime(): array
    {
        return $this->crawler
            ->filter('div.seasonal-anime.js-seasonal-anime')
            ->each(
                function (Crawler $animeCrawler) {
                    return (new AnimeCardParser($animeCrawler))->getSeasonalModel();
                }
            );
    }

    /**
     * @return string|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getSeasonName(): ?string
    {
        $node = $this->crawler->filter('div.navi-seasonal a.on');

        if (!$node->count()) {
            return null;
        }

        $season = explode(
            ' ',
            JString::cleanse(
                $node->text()
            )
        );

        return $season[0];
    }

    /**
     * @return int|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getSeasonYear(): ?int
    {
        $node = $this->crawler->filter('div.navi-seasonal a.on');

        if (!$node->count()) {
            return null;
        }

        $season = explode(
            ' ',
            JString::cleanse(
                $this->crawler->filter('div.navi-seasonal a.on')->text()
            )
        );

        if (!isset($season[1])) {
            return null;
        }

        return $season[1];
    }
}
