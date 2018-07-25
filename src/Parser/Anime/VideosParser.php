<?php

namespace Jikan\Parser\Anime;

use Jikan\Model\Anime\AnimeVideos;
use Jikan\Model\Anime\PromoListItem;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class VideosParser
 *
 * @package Jikan\Parser
 */
class VideosParser implements ParserInterface
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
     * @return \Jikan\Model\Anime\StreamEpisodeListItem[]
     * @throws \InvalidArgumentException
     */
    public function getEpisodes(): array
    {
        $episodes = $this->crawler
            ->filterXPath('//div[@class="js-video-list-content"]/div[contains(@class, "video-list-outer")]');

        if (!$episodes->count()) {
            return [];
        }

        return $episodes
            ->each(
                function (Crawler $crawler) {
                    return (new StreamEpisodeListItemParser($crawler))->getModel();
                }
            );
    }

    /**
     * @return PromoListItem[]
     * @throws \InvalidArgumentException
     */
    public function getPromos(): array
    {
        $promos = $this->crawler
            ->filterXPath('//div[contains(@class, "video-block promotional-video")]/section/div');

        if (!$promos->count()) {
            return [];
        }


        return $promos
            ->each(
                function (Crawler $crawler) {
                    return (new PromoListItemParser($crawler))->getModel();
                }
            );
    }

    /**
     * Return the model
     *
     * @throws \InvalidArgumentException
     */
    public function getModel(): AnimeVideos
    {
        return AnimeVideos::fromParser($this);
    }
}
