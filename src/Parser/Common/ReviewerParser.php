<?php

namespace Jikan\Parser\Common;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Anime\AnimeReview;
use Jikan\Model\Anime\AnimeReviewer;
use Jikan\Model\Anime\AnimeReviewScores;
use Jikan\Parser\Anime\AnimeReviewScoresParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ReviewerParser
 *
 * @package Jikan\Parser
 */
class ReviewerParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * ReviewerParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return AnimeReviewer
     * @throws \Exception
     * @throws \RuntimeException
     */
    public function getModel(): AnimeReviewer
    {
        return AnimeReviewer::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return $this->crawler->filterXPath('//div[1]/div[1]/div[2]/table/tr/td[2]/a')->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUsername(): string
    {
        return $this->crawler->filterXPath('//div[1]/div[1]/div[2]/table/tr/td[2]/a')->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getImageUrl(): string
    {
        return Parser::parseImageThumbToHQ(
            $this->crawler->filterXPath('//div[1]/div[1]/div[2]/table/tr/td[1]/div/a/img')
                ->attr('src')
        );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getEpisodesSeen(): int
    {
        $nodeText = JString::cleanse(
            $this->crawler->filterXPath('//div[1]/div[1]/div[1]/div[2]')->text()
        );

        preg_match('~(\d+) of (\d+) episodes seen~', $nodeText, $episodesSeen);

        if (empty($episodesSeen)) {
            return 0;
        }

        return (int) $episodesSeen[1];
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getChaptersRead(): int
    {
        return 0;
    }

    /**
     * @return AnimeReviewScores
     * @throws \InvalidArgumentException
     */
    public function getAnimeScores(): AnimeReviewScores
    {
        return (new AnimeReviewScoresParser($this->crawler))->getModel();
    }

    //for manga
//    /**
//     * @return AnimeReviewScores
//     * @throws \InvalidArgumentException
//     */
//    public function getMangaScores(): AnimeReviewScores
//    {
//        return new AnimeReviewScores();
//    }
}
