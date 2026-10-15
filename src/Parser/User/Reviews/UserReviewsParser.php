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
    private Crawler $crawler;

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

    public function getReviews(): array
    {
        $node = $this->crawler->filterXPath('//*[@id="content"]/table/tbody/tr/td[2]//div[contains(@class, "review-element")]');

        if (!$node->count()) {
            return [];
        }

        return $node->each(function (Crawler $crawler) {
            // Anime Review
            if ($crawler->filterXPath('//div/div/div[2]/div[2]/small')->text() === '(Anime)') {
                return Model\User\Reviews\UserAnimeReview::fromParser(new AnimeReviewParser($crawler));
            }

            // Manga Review
            if ($crawler->filterXPath('//div/div/div[2]/div[2]/small')->text() === '(Manga)') {
                return Model\User\Reviews\UserMangaReview::fromParser(new MangaReviewParser($crawler));
            }
        });
    }
    public function hasNextPage(): bool
    {
        return $this->nextPage() !== null;
    }

    /**
     * The page only links the next one ("More Reviews", ?p=N, 10 reviews per page), not the last, so this is the
     * furthest page it lets you reach: the next page when there is one.
     */
    public function getLastVisiblePage(): ?int
    {
        return $this->nextPage() ?? $this->currentPage();
    }

    private function currentPage(): int
    {
        return preg_match('~[?&]p=(\d+)~', $this->crawler->getUri() ?? '', $m) ? (int) $m[1] : 1;
    }

    /** The lowest linked page after the current one ("More Reviews"), or null on the last page. */
    private function nextPage(): ?int
    {
        $current = $this->currentPage();
        $pages = [];
        foreach ($this->crawler->filterXPath('//a[contains(@href, "/reviews?p=")]') as $link) {
            if ($link instanceof \DOMElement && preg_match('~[?&]p=(\d+)~', $link->getAttribute('href'), $m)) {
                $pages[] = (int) $m[1];
            }
        }
        $later = array_filter($pages, static fn(int $page): bool => $page > $current);

        return $later === [] ? null : min($later);
    }
}
