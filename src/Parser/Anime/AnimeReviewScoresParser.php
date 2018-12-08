<?php

namespace Jikan\Parser\Anime;

use Jikan\Model\Anime\AnimeReviewScores;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimeReviewScoresParser
 *
 * @package Jikan\Parser
 */
class AnimeReviewScoresParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * AnimeParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return AnimeReviewScores
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): AnimeReviewScores
    {
        return AnimeReviewScores::fromParser($this);
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
    public function getAnimationScore() : int
    {
        return (int) $this->crawler->filterXPath('//table/tr[3]/td[2]')->text();
    }

    /**
     * @return int
     */
    public function getSoundScore() : int
    {
        return (int) $this->crawler->filterXPath('//table/tr[4]/td[2]')->text();
    }

    /**
     * @return int
     */
    public function getCharacterScore() : int
    {
        return (int) $this->crawler->filterXPath('//table/tr[5]/td[2]')->text();
    }

    /**
     * @return int
     */
    public function getEnjoymentScore() : int
    {
        return (int) $this->crawler->filterXPath('//table/tr[6]/td[2]')->text();
    }
}
