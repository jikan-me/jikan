<?php

namespace Jikan\Parser\Anime;

use Jikan\Helper\Parser;
use Jikan\Model\Anime\AnimeReview;
use Jikan\Model\Anime\AnimeReviewer;
use Jikan\Model\Anime\AnimeReviewScores;
use Jikan\Parser\Common\ReviewerParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimeReviewParser
 *
 * @package Jikan\Parser
 */
class AnimeReviewParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * AnimeReviewParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return AnimeReview
     * @throws \Exception
     * @throws \RuntimeException
     */
    public function getModel(): AnimeReview
    {
        return AnimeReview::fromParser($this);

    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getId(): int
    {
        return 0;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return "";
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getHelpfulCount(): int
    {
        return 0;
    }

    /**
     * @return \DateTimeImmutable
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function getDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getContent(): string
    {
        echo htmlentities(
            $this->crawler
                ->filterXPath('//div[contains(@class, "textReadability")]')
                ->html()
        );
        echo "<br><br>";

        return $this->crawler
            ->filterXPath('//div[2]')
            ->text();
    }

    /**
     * @return AnimeReviewer
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function getReviewer(): AnimeReviewer
    {
        return (new ReviewerParser($this->crawler))->getModel();
    }


}
