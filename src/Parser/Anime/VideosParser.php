<?php

namespace Jikan\Parser\Anime;

use Jikan\Model\StreamEpisodeListItem;
use Jikan\Model\AnimeVideos;
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
     * @return StreamEpisodeListItem[]
     */
    public function getEpisodes(): array
    {
        return [];
    }

    /**
     * @return PromoListItem[]
     */
    public function getPromos(): array
    {
        return [];
    }

    /**
     * Return the model
     */
    public function getModel(): AnimeVideos
    {
        return AnimeVideos::fromParser($this);
    }
}
