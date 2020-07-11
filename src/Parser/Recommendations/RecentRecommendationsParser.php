<?php

namespace Jikan\Parser\Recommendations;

use Jikan\Model\Anime\AnimeReview;
use Jikan\Model\Manga\MangaReview;
use Jikan\Model\Recommendations\RecentRecommendations;
use Jikan\Model\Recommendations\RecommendationListItem;
use Jikan\Model\Reviews\RecentReviews;
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
     * @return RecentReviews
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
//        $node = $this->crawler
//            ->filterXPath('//*[@id="horiznav_nav"]/div/a[contains(text(), "Next")]');
//
//        if ($node->count()) {
//            return true;
//        }

        // TODO: return true until I figure out how to process this
        // (there's like 760+ pages and a page that does not exist, returns HTTP 404)
        // Same implementation for Search Results
        return true;
    }
}
