<?php

namespace Jikan\Parser\Reviews;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Common\AnimeMeta;
use Jikan\Model\Common\MangaMeta;
use Jikan\Model\Manga\MangaReview;
use Jikan\Model\Manga\MangaReviewer;
use Jikan\Model\Manga\MangaReviewScores;
use Jikan\Model\Reviews\Reviewer;
use Jikan\Parser\Manga\MangaReviewScoresParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MangaReviewParser
 *
 * @package Jikan\Parser
 */
class MangaReviewParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * MangaReviewParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return MangaReview
     * @throws \Exception
     * @throws \RuntimeException
     */
    public function getModel(): MangaReview
    {
        return MangaReview::fromParser($this);
    }

    public function getManga() : MangaMeta
    {
        return new MangaMeta(
            $this->getReviewedTitle(),
            $this->getReviewedUrl(),
            $this->getReviewedImageUrl()
        );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getId(): int
    {
        parse_str(parse_url($this->getUrl(), PHP_URL_QUERY), $params);
        return (int) $params['id'];
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        $node = $this->crawler->filterXPath('//div[1]/div[3]/div/div/a');
        return $node->attr('href');
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getHelpfulCount(): int
    {
        // works on Anime/Manga Review pages
        $node = $this->crawler->filterXPath('//div[1]/div[1]/div[2]/table/tr/td[2]/div/strong/span');
        if ($node->count()) {
            return $node->text();
        }

        // works on Top UserReviewsParser pages, the div is shifted
        $node = $this->crawler->filterXPath('//div[1]/div[1]/div[4]/table/tr/td[2]/div/strong/span');
        if ($node->count()) {
            return $node->text();
        }

        // works on User UserReviews pages
        $node = $this->crawler->filterXPath('//div[1]/div[1]/div[4]/table/tr/td/div/strong/span');
        return $node->text();
    }

    /**
     * @return \DateTimeImmutable
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function getDate(): \DateTimeImmutable
    {
        $date = $this->crawler->filterXPath('//div[1]/div[1]/div[1]/div[1]')->text();
        $time = $this->crawler->filterXPath('//div[1]/div[1]/div[1]/div[1]')->attr('title');
        return new \DateTimeImmutable("{$date} {$time}", new \DateTimeZone('UTC'));
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getContent(): string
    {
        //        echo htmlentities(
        //            $this->crawler
        //                ->filterXPath('//div[contains(@class, "textReadability")]')
        //                ->html()
        //        );
        //        echo "<br><br>";
        //
        //        return $this->crawler
        //            ->filterXPath('//div[2]')
        //            ->text();
        $node = $this->crawler->filterXPath('//div[1]/div[2]');
        $nodeExpanded = $node->filterXPath('//span');

        $node = Parser::removeChildNodes($node);

        $content = JString::cleanse($node->text());

        if ($nodeExpanded->count()) {
            $expandedContent = JString::cleanse(Parser::removeChildNodes($nodeExpanded)->html());
            $content .= $expandedContent;
        }

        return $content;
    }

    /**
     * @return Reviewer
     */
    public function getReviewer(): Reviewer
    {
        return (new ReviewerParser($this->crawler))->getModel();
    }

    /**
     * @return MangaReviewScores
     * @throws \InvalidArgumentException
     */
    public function getMangaScores(): MangaReviewScores
    {
        return (new MangaReviewScoresParser($this->crawler))->getModel();
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        $node = $this->crawler->filterXPath('//div[1]/div[1]/div[2]/small');

        if (!$node->count()) {
            return null;
        }

        return strtolower(
            str_replace(
                ['(', ')'],
                '',
                $node->text()
            )
        );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getReviewedTitle(): string
    {
        return $this->crawler
            ->filterXPath('//div[1]/div[1]/div[2]/strong/a')
            ->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getReviewedImageUrl(): string
    {
        // User UserReviews page
        $node = $this->crawler
            ->filterXPath('//div[12]/div[1]/div[1]/a/img');

        if ($node->count()) {
            return Parser::parseImageQuality($node->attr('data-src'));
        }

        // Recent UserReviews Anime page
        $node = $this->crawler
            ->filterXPath('//div[1]/div[2]/div[1]/div[1]/a/img');

        if ($node->count()) {
            return Parser::parseImageQuality($node->attr('data-src'));
        }
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getReviewedUrl(): string
    {
        // User UserReviews page
        $node = $this->crawler
            ->filterXPath('//div[12]/div[1]/div[1]/a');

        if ($node->count()) {
            return $node->attr('href');
        }

        // Recent UserReviews Anime page
        $node = $this->crawler
            ->filterXPath('//div[1]/div[2]/div[1]/div[1]/a');

        return $node->attr('href');
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
}
