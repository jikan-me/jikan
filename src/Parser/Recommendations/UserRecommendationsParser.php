<?php

namespace Jikan\Parser\Recommendations;

use Jikan\Model\Anime\AnimeReview;
use Jikan\Model\Manga\MangaReview;
use Jikan\Model\Recommendations\RecentRecommendations;
use Jikan\Model\Recommendations\RecommendationListItem;
use Jikan\Model\Recommendations\UserRecommendations;
use Jikan\Model\Reviews\Reviews;
use Jikan\Parser\Anime\AnimeReviewParser;
use Jikan\Parser\Manga\MangaReviewParser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class RecentRecommendationsParser
 *
 * @package Jikan\Parser\Top
 */
class UserRecommendationsParser
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
    public function getModel(): UserRecommendations
    {
        return UserRecommendations::fromParser($this);
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
        $node = $this->crawler
            ->filterXPath('//*[@id="content"]/div/div[2]/div/div[2]/div[1]/a[contains(text(), "More Recommendations")]');

        if ($node->count()) {
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
            ->filterXPath('//*[@id="content"]/div/div[2]/div/div[2]/div[2]/div[contains(text(), "Total Recommendations:")]');

        if (!$pages->count()) {
            return 1;
        }

        preg_match('~Total Recommendations: (.*)$~', $pages->text(), $pages);

        if (empty($pages)) {
            return 1;
        }

        $recommendationsCount = (int) str_replace(',', '', $pages[1]);

        return ceil($recommendationsCount/30);
    }
}
