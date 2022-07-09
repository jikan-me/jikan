<?php

namespace Jikan\Parser\Anime;

use Jikan\Model\Anime\AnimeVideos;
use Jikan\Model\Anime\MusicVideoListItem;
use Jikan\Model\Anime\AnimeVideosEpisodes;
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
    private Crawler $crawler;

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
            ->filterXPath('//*[@id="content"]/table/tr/td[2]/div[2]/div[2]/div[contains(@class, "video-block episode-video")]//*[contains(@class, "video-list-outer")]');

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
     * @return MusicVideoListItem[]
     */
    public function getMusic(): array
    {
        $node = $this->crawler
            ->filterXPath('//div[contains(@class, "video-block music-video")]/section/div');

        if (!$node->count()) {
            return [];
        }

        return $node
            ->each(
                function (Crawler $crawler) {
                    return (new MusicVideoListItemParser($crawler))->getModel();
                }
            );
    }

    /**
     * @return bool
     */
    public function getHasNextPage(): bool
    {
        $node = $this->crawler
            ->filterXPath('//div[contains(@class, "video-block episode-video")]//div[contains(@class, "pagination")]/a[text()[contains(.,"More")]]');

        if ($node->count()) {
            return true;
        }

        return false;
    }

    /**
     * @return int
     */
    public function getLastPage(): int
    {
        $node = $this->crawler
            ->filterXPath('//div[contains(@class, "video-block episode-video")]//div[contains(@class, "pagination")]/a[text()[contains(.,"Last")]]');

        // All pages except the last page returns "Last" button
        if ($node->count()) {
            parse_str(
                parse_url($node->attr('href'), PHP_URL_QUERY),
                $params
            );

            return (int) $params['p'];
        }

        // Fallback 1
        // The second last page doesn't return "Last" and only returns "More" as an indicator
        // for the next or last page
        $node = $this->crawler
            ->filterXPath('//div[contains(@class, "video-block episode-video")]//div[contains(@class, "pagination")]/a[text()[contains(.,"More")]]');

        if ($node->count()) {
            parse_str(
                parse_url($node->attr('href'), PHP_URL_QUERY),
                $params
            );

            return (int) $params['p'];
        }

        // Fallback 2
        // The last page only indicates the last page through a span element
        $node = $this->crawler
            ->filterXPath('//div[contains(@class, "video-block episode-video")]//div[contains(@class, "pagination")]/*[position()=last()]');

        // Fallback 3
        // The user has exceeded the pagination
        // MAL still generates pagination here for some reason
        // So we'll check other properties to see whether the page has ended or not
        // otherwise fallback 2 will keep returning the generated page
        $hasReachedTheEnd = $this->crawler
            ->filterXPath('//div[contains(@class, "video-block episode-video")]//p[text()[contains(.,"No episode video has been added to this title")]]');

        if ($hasReachedTheEnd->count()) {
            // there is no way for us to know
            // what the last accessible page is
            // e.g https://myanimelist.net/anime/21/One_Piece/video?p=300
            return 1;
        }


        if ($node->count()) {
            // this element is not clickable and is returned as text

            return (int) $node->text();
        }

        // if anything breaks
        return 1;
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

    /**
     * Return the results based model
     *
     * @throws \InvalidArgumentException
     */
    public function getResultsModel(): AnimeVideosEpisodes
    {
        return AnimeVideosEpisodes::fromParser($this);
    }
}
