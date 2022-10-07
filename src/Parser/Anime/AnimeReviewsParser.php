<?php

namespace Jikan\Parser\Anime;

use Jikan\Model\Anime\AnimeReviews;
use Jikan\Parser\ParserInterface;
use Jikan\Parser\Reviews\AnimeReviewParser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimeReviewsParser
 *
 * @package Jikan\Parser
 */
class AnimeReviewsParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * AnimeReviewsParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return AnimeReviews
     */
    public function getModel() : AnimeReviews
    {
        return AnimeReviews::fromParser($this);
    }

    /**
     * @return Crawler
     */
    public function getCrawler(): Crawler
    {
        return $this->crawler;
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
                    return (new AnimeReviewParser($c))->getModel();
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
