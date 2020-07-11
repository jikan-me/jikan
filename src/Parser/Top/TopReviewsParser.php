<?php

namespace Jikan\Parser\Top;

use Jikan\Model\Anime\AnimeReview;
use Jikan\Model\Manga\MangaReview;
use Jikan\Parser\Anime\AnimeReviewParser;
use Jikan\Parser\Anime\AnimeReviewsParser;
use Jikan\Parser\Manga\MangaReviewParser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TopReviewsParser
 *
 * @package Jikan\Parser\Top
 */
class TopReviewsParser
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
     * @return TopAnime[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getTopReviews(): array
    {
        return $this->crawler
            ->filterXPath('//*[@id="content"]/div[@class="borderDark"]')
            ->each(
                function (Crawler $crawler) {
                    // If requested by $type `Constants::TOP_REVIEWS_BEST_VOTED`; both types can be returned
                    // So we check types here to allow the ability to parse and return both

                    // Anime Review
                    if ($crawler->filterXPath('//div[1]/div[1]/div[2]/small')->text() === '(Anime)') {
                        return AnimeReview::fromParser(new AnimeReviewParser($crawler));
                    }

                    // Manga Review
                    if ($crawler->filterXPath('//div[1]/div[1]/div[2]/small')->text() === '(Manga)') {
                        return MangaReview::fromParser(new MangaReviewParser($crawler));
                    }
                }
            );
    }
}
