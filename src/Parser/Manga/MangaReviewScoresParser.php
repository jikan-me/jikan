<?php

namespace Jikan\Parser\Manga;

use Jikan\Model\Manga\MangaReviewScores;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MangaReviewScoresParser
 *
 * @package Jikan\Parser
 */
class MangaReviewScoresParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * MangaReviewScoresParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return MangaReviewScores
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): MangaReviewScores
    {
        return MangaReviewScores::fromParser($this);
    }

    /**
     * @return int
     */
    public function getOverallScore() : int
    {
        return (int) $this->crawler->filterXPath('//table/tr[1]/td[2]/strong')->text();
    }
    /**
     * @return int
     */
    public function getStoryScore() : int
    {
        return (int) $this->crawler->filterXPath('//table/tr[2]/td[2]')->text();
    }

    /**
     * @return int
     */
    public function getArtScore() : int
    {
        return (int) $this->crawler->filterXPath('//table/tr[3]/td[2]')->text();
    }

    /**
     * @return int
     */
    public function getCharacterScore() : int
    {
        return (int) $this->crawler->filterXPath('//table/tr[4]/td[2]')->text();
    }

    /**
     * @return int
     */
    public function getEnjoymentScore() : int
    {
        return (int) $this->crawler->filterXPath('//table/tr[5]/td[2]')->text();
    }
}
