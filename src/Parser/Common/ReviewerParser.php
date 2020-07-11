<?php

namespace Jikan\Parser\Common;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Anime\AnimeReviewScores;
use Jikan\Model\Manga\MangaReviewScores;
use Jikan\Parser\Anime\AnimeReviewScoresParser;
use Jikan\Parser\Manga\MangaReviewScoresParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ReviewerParser
 *
 * @package Jikan\Parser
 */
abstract class ReviewerParser implements ParserInterface
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
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        // works on Anime/Manga Review pages
        $node = $this->crawler->filterXPath('//div[1]/div[1]/div[2]/table/tr/td[2]/a');
        if ($node->count()) {
            return $node->attr('href');
        }

        // works on Top Reviews pages, the div is shifted
        $node = $this->crawler->filterXPath('//div[1]/div[1]/div[4]/table/tr/td[2]/a');
        return $node->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUsername(): string
    {
        // works on Anime/Manga Review pages
        $node = $this->crawler->filterXPath('//div[1]/div[1]/div[2]/table/tr/td[2]/a');
        if ($node->count()) {
            return $node->text();
        }

        // works on Top Reviews pages, the div is shifted
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
        $node = $this->crawler->filterXPath('//div[1]/div[1]/div[2]/table/tr/td[1]/div/a/img');
        if ($node->count()) {
            return Parser::parseImageThumbToHQ(
                $node->attr('data-src')
            );
        }

        // works on Top Reviews pages, the div is shifted
        $node = $this->crawler->filterXPath('//div[1]/div[1]/div[4]/table/tr/td[1]/div/a/img');
        return Parser::parseImageThumbToHQ(
            $node
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

        preg_match('~(\d+) of (.*) episodes seen~', $nodeText, $episodesSeen);

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
        $nodeText = JString::cleanse(
            $this->crawler->filterXPath('//div[1]/div[1]/div[1]/div[2]')->text()
        );

        preg_match('~(\d+) of (.*) chapters read~', $nodeText, $chaptersRead);

        if (empty($chaptersRead)) {
            return 0;
        }

        return (int) $chaptersRead[1];
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
