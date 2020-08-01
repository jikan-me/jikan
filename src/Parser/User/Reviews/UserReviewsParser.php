<?php

namespace Jikan\Parser\User\Reviews;

use Jikan\Model;
use Jikan\Parser\Reviews\AnimeReviewParser;
use Jikan\Parser\Reviews\MangaReviewParser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class UserReviewsParser
 *
 * @package Jikan\Parser
 */
class UserReviewsParser
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

    public function getModel(): Model\User\Reviews\UserReviews
    {
        return Model\User\Reviews\UserReviews::fromParser($this);
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
                return Model\User\Reviews\UserAnimeReview::fromParser(new AnimeReviewParser($crawler));
            }

            // Manga Review
            if ($crawler->filterXPath('//div[2]/div[2]/small[1]')->text() === '(Manga)') {
                return Model\User\Reviews\UserMangaReview::fromParser(new MangaReviewParser($crawler));
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
        return 1;
    }
}
