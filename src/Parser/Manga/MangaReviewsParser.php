<?php

namespace Jikan\Parser\Manga;

use Jikan\Model\Manga\MangaReviews;
use Jikan\Parser\ParserInterface;
use Jikan\Parser\Reviews\MangaReviewParser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MangaReviewsParser
 *
 * @package Jikan\Parser
 */
class MangaReviewsParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * MangaReviewsParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    public function getModel() : MangaReviews
    {
        return MangaReviews::fromParser($this);
    }

    /**
     * @return array
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getResults(): array
    {
        return $this->crawler
            ->filterXPath('//div[contains(@class, "rightside")]//div[contains(@class, "review-element")]')
            ->each(
                function (Crawler $c) {
                    return (new MangaReviewParser($c))->getModel();
                }
            );
    }

    /**
     * @return bool
     */
    public function hasNextPage(): bool // @TODO WIP
    {
        $node = $this->crawler
            ->filterXPath('//*[@id="horiznav_nav"]/div/a[contains(text(), "Next")]');

        if ($node->count()) {
            return true;
        }

        return false;
    }
}
