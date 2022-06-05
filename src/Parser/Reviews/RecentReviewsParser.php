<?php

namespace Jikan\Parser\Reviews;

use Jikan\Model\Reviews\Recent\RecentAnimeReview;
use Jikan\Model\Reviews\Recent\RecentMangaReview;
use Jikan\Model\Reviews\RecentReviews;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TopReviewsParser
 *
 * @package Jikan\Parser\Top
 */
class RecentReviewsParser
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
    public function getModel(): RecentReviews
    {
        return RecentReviews::fromParser($this);
    }

    /**
     * @return array
     * @throws \RuntimeException
     * @throws \InvalidArgumentException|\Exception
     */
    public function getRecentReviews(): array
    {
        return array_filter(
            $this->crawler
                ->filterXPath('//*[@id="content"]/div[@class="borderDark"]')
                ->each(
                    function (Crawler $crawler) {
                        // If requested by $type `Constants::TOP_REVIEWS_BEST_VOTED`; both types can be returned
                        // So we check types here to allow the ability to parse and return both

                        // Anime Review
                        if ($crawler->filterXPath('//div[1]/div[1]/div[2]/small')->text() === '(Anime)') {
                            return RecentAnimeReview::fromParser(new AnimeReviewParser($crawler));
                        }

                        // Manga Review
                        if ($crawler->filterXPath('//div[1]/div[1]/div[2]/small')->text() === '(Manga)') {
                            return RecentMangaReview::fromParser(new MangaReviewParser($crawler));
                        }

                        return null;
                    }
                )
        );
    }

    /**
     * @return bool
     */
    public function hasNextPage(): bool
    {
        $node = $this->crawler
            ->filterXPath('//*[@id="horiznav_nav"]/div/a[contains(text(), "Next")]');

        if ($node->count()) {
            return true;
        }

        return false;
    }
}
