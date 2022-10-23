<?php

namespace Jikan\Parser\Reviews;

use Jikan\Model\Reviews\FullAnimeReview;
use Jikan\Model\Reviews\FullMangaReview;
use Jikan\Model\Reviews\Reviews;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TopReviewsParser
 *
 * @package Jikan\Parser\Top
 */
class ReviewsParser
{
    /**
     * @var Crawler
     */
    private Crawler $crawler;

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
    public function getModel(): Reviews
    {
        return Reviews::fromParser($this);
    }

    /**
     * @return array
     * @throws \RuntimeException
     * @throws \InvalidArgumentException|\Exception
     */
    public function getReviews(): array
    {
        return array_filter(
            $this->crawler
                ->filterXPath('//*[@id="content"]//div[contains(@class, "review-element")]')
                ->each(
                    function (Crawler $crawler) {
                        // Anime Review
                        if ($crawler->filterXPath('//div/small')->text() === '(Anime)') {
                            return FullAnimeReview::fromParser(new AnimeReviewParser($crawler));
                        }

                        // Manga Review
                        if ($crawler->filterXPath('//div/small')->text() === '(Manga)') {
                            return FullMangaReview::fromParser(new MangaReviewParser($crawler));
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
