<?php

namespace Jikan\Parser\Recommendations;

use Jikan\Model\Anime\AnimeReview;
use Jikan\Model\Manga\MangaReview;
use Jikan\Model\Recommendations\RecentRecommendations;
use Jikan\Model\Recommendations\RecommendationListItem;
use Jikan\Model\Reviews\Reviews;
use Jikan\Parser\Anime\AnimeReviewParser;
use Jikan\Parser\Manga\MangaReviewParser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class RecentRecommendationsParser
 *
 * @package Jikan\Parser\Top
 */
class RecentRecommendationsParser
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
     * @return Reviews
     * @throws \Exception
     */
    public function getModel(): RecentRecommendations
    {
        return RecentRecommendations::fromParser($this);
    }

    /**
     * @return array
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getRecentRecommendations(): array
    {
        return $this->crawler
            ->filterXPath('//*[@id="content"]/div[3]/div[contains(@class, "spaceit borderClass")]')
            ->each(
                function (Crawler $crawler) {
                    return RecommendationListItem::fromParser(new RecommendationListItemParser($crawler));
                }
            );
    }

    /**
     * @return array
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getUserRecommendations(): array
    {
        return $this->crawler
            ->filterXPath('//*[@id="content"]/div/div[2]/div/div[2]/div[contains(@class, "spaceit borderClass")]')
            ->each(
                function (Crawler $crawler) {
                    return RecommendationListItem::fromParser(new RecommendationListItemParser($crawler));
                }
            );
    }

    /**
     * @return bool
     */
    public function hasNextPage(): bool
    {
        $pages = $this->crawler
            ->filterXPath('//*[@id="horiznav_nav"]/div/span');

        if (!$pages->count()) {
            return false;
        }

        if (preg_match('~\[\d+]\s(\d+)~', $pages->text())) {
            return true;
        }

        return false;
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getLastPage(): int
    {
        $pages = $this->crawler
            ->filterXPath('//*[@id="horiznav_nav"]/div/span');

        if (!$pages->count()) {
            return 1;
        }

        $pages = explode(' ', $pages->text());

        return (int) str_replace(['[', ']'], '', end($pages));
    }
}
