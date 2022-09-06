<?php

namespace Jikan\Parser\Reviews;

use Jikan\Exception\ParserException;
use Jikan\Helper\Parser;
use Jikan\Model\Anime\AnimeReviewScores;
use Jikan\Model\Manga\MangaReviewScores;
use Jikan\Model\Reviews\Reviewer;
use Jikan\Parser\Manga\MangaReviewScoresParser;
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

    public function getModel()
    {
        return Reviewer::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     * @throws \Jikan\Exception\ParserException
     */
    public function getUrl(): string
    {
        // works on Anime/Manga Review pages
        $node = $this->crawler->filterXPath('//div/div[2]/div[contains(@class, "username")]/a');
        if ($node->count()) {
            return $node->attr('href');
        }

        // works on Top UserReviewsParser pages, the div is shifted
        $node = $this->crawler->filterXPath('//div[1]/div[1]/div[4]/table/tr/td[2]/a');
        if ($node->count()) {
            return $node->attr('href');
        }

        throw new ParserException("Couldn't find any URL on review pages.");
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUsername(): string
    {
        // works on Anime/Manga Review pages
        $node = $this->crawler->filterXPath('//div/div[2]/div[contains(@class, "username")]/a');
        if ($node->count()) {
            return $node->text();
        }

        // works on Top UserReviewsParser pages, the div is shifted
        return $this->crawler
            ->filterXPath('//div[1]/div[1]/div[4]/table/tr/td[2]/a')
            ->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getImageUrl(): string
    {
        // works on Anime/Manga Review pages
        $node = $this->crawler->filterXPath('//div/div/a/img');
        if ($node->count()) {
            return Parser::parseImageThumbToHQ(
                $node->attr('data-src')
            );
        }

        // works on Top UserReviewsParser pages, the div is shifted
        $node = $this->crawler->filterXPath('//div[1]/div[1]/div[4]/table/tr/td[1]/div/a/img');
        return Parser::parseImageThumbToHQ(
            $node
                ->attr('src')
        );
    }

    /**
     * @return AnimeReviewScores
     * @throws \InvalidArgumentException
     */
    public function getAnimeScores(): AnimeReviewScores
    {
        return (new AnimeReviewScoresParser($this->crawler))->getModel();
    }

    /**
     * @return MangaReviewScores
     * @throws \InvalidArgumentException
     */
    public function getMangaScores(): MangaReviewScores
    {
        return (new MangaReviewScoresParser($this->crawler))->getModel();
    }
}
