<?php

namespace Jikan\Parser\Anime;

use Jikan\Model\EpisodeListItem;
use Jikan\Model\Episodes;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class EpisodseParser
 *
 * @package Jikan\Parser
 */
class EpisodesParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * EpisodesParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return EpisodeListItem[]
     */
    public function getEpisodes(): array
    {
        $node = $this->crawler
            ->filterXPath('//table[contains(@class, \'js-watch-episode-list ascend\')]/tr');

        return [];
            // ->each(
            //     function (Crawler $crawler) {
            //         return (new EpisodeListItemParser($crawler))->getModel();
            //     }
            // );
    }

    /**
     * Return the model
     */
    public function getModel(): Episodes
    {
        return Episodes::fromParser($this);
    }
}
