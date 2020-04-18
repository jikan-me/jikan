<?php

namespace Jikan\Parser\User;

use Jikan\Model;
use Jikan\Model\Anime\AnimeReview;
use Jikan\Model\Manga\MangaReview;
use Jikan\Parser\Anime\AnimeReviewParser;
use Jikan\Parser\Manga\MangaReviewParser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ReviewsParser
 *
 * @package Jikan\Parser
 */
class ReviewsParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * UsernameByIdParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    public function getModel(): Model\User\Reviews
    {
        return Model\User\Reviews::fromParser($this);
    }

    public function getReviews() : array
    {
        $node = $this->crawler->filterXPath('//*[@id="content"]/table/tr/td[2]/div[@class="borderDark"]');

        if (!$node->count()) {
            return [];
        }

        return $node->each(function (Crawler $crawler) {

            // Anime Review
            if ($crawler->filterXPath('//div[2]/div[2]/small[1]')->text() === '(Anime)') {
                return AnimeReview::fromParser(new AnimeReviewParser($crawler));
            }

            // Manga Review
            if ($crawler->filterXPath('//div[2]/div[2]/small[1]')->text() === '(Manga)') {
                return MangaReview::fromParser(new MangaReviewParser($crawler));
            }
        });
    }

    public function hasNextPage() : bool
    {
        // TODO: Add implementation
        return true;
    }

    public function getLastVisiblePage() : ?int
    {
        // TODO: Add implementation
        return null;
    }
}
