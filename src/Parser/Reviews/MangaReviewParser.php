<?php

namespace Jikan\Parser\Reviews;

use Jikan\Exception\ParserException;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Common\MangaMeta;
use Jikan\Model\Manga\MangaReview;
use Jikan\Model\Manga\MangaReviewScores;
use Jikan\Model\Reviews\Reactions;
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
     * @var \Symfony\Component\DomCrawler\Crawler
     */
    private Crawler $crawler;

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
     * @return \Jikan\Model\Manga\MangaReview
     * @throws \Exception
     * @throws \RuntimeException
     */
    public function getModel(): MangaReview
    {
        return MangaReview::fromParser($this);
    }

    /**
     * @throws \Jikan\Exception\ParserException
     */
    public function getManga(string $page = null) : MangaMeta
    {
        return match ($page) {
            'user' => new MangaMeta(
                $this->getMangaTitle(),
                $this->getMangaUrl(),
                $this->getMangaImageUrlFromUserPage()
            ),
            default => new MangaMeta(
                $this->getMangaTitle(),
                $this->getMangaUrl(),
                $this->getMangaImageUrl()
            ),
        };
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
        $node = $this->crawler
            ->filterXPath('//div/div[2]/div[contains(@class, "bottom-navi")]/div[@class="open"]/a');
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
        $node = $this->crawler->filterXPath('//div/div[2]/div[contains(@class, "update_at")]');
        $date = $node->text();
        $time = $node->attr('title');
        return new \DateTimeImmutable("{$date} {$time}", new \DateTimeZone('UTC'));
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getContent(): string
    {
        $node = $this->crawler->filterXPath('//div/div[2]/div[contains(@class, "text")]');
        $nodeExpanded = $this
            ->crawler
            ->filterXPath('//div/div[2]/div[contains(@class, "text")]/span[contains(@class, "js-hidden")]');

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
        // Anime/Manga and User Reviews page
        $node = $this->crawler->filterXPath('//div/div/div[2]/div[2]/small');

        if ($node->count()) {
            return strtolower(
                str_replace(
                    ['(', ')'],
                    '',
                    $node->text()
                )
            );
        }

        // All Reviews Page
        $node = $this->crawler->filterXPath('//div/small');

        if ($node->count()) {
            return strtolower(
                str_replace(
                    ['(', ')'],
                    '',
                    $node->text()
                )
            );
        }

        return null;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getMangaTitle(): string
    {
        return $this->crawler
            ->filterXPath('//div[contains(@class, "titleblock")]/a')
            ->text();
    }

    /**
     * @return string
     */
    public function getMangaImageUrl(): string
    {
        $node = $this->crawler
            ->filterXPath('//div[contains(@class, "thumbbody")]/div[contains(@class, "body")]/div[contains(@class, "text")]/div[contains(@class, "thumb-right")]/a/img');

        return Parser::parseImageQuality($node->attr('data-src'));
    }

    /**
     * @return string
     */
    public function getMangaImageUrlFromUserPage(): string
    {
        $node = $this->crawler
            ->filterXPath('//div[contains(@class, "thumbbody")]/div[contains(@class, "thumb")]/a/img');

        return Parser::parseImageQuality($node->attr('data-src'));
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getMangaUrl(): string
    {
        // User UserReviews page
//        $node = $this->crawler
//            ->filterXPath('//div[1]/div/div[2]/div/a');
//
//        if ($node->count()) {
//            return $node->attr('href');
//        }

        // Recent UserReviews Anime page
        $node = $this->crawler
            ->filterXPath('//div[contains(@class, "titleblock")]/a');

        return $node->attr('href');
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getChaptersRead(): ?int
    {
        $node = $this->crawler->filterXPath('//div/div[2]/div[contains(@class, "tags")]/div[contains(@class, "preliminary")]/span');

        if (!$node->count()) {
            return null;
        }

        preg_match('~\((\d+)/(.*)\)~', JString::cleanse($node->text()), $episodesSeen);

        if (empty($episodesSeen)) {
            return 0;
        }

        return (int) $episodesSeen[1];
    }

    /**
     * @return Reactions
     */
    public function getReactions(): Reactions
    {
        return (new ReactionsParser($this->crawler))->getModel();
    }

    /**
     * @return int
     */
    public function getReviewerScore(): int
    {
        return (int) $this->crawler
            ->filterXPath('//div/div[2]/div[contains(@class, "rating")]/span')
            ->text();
    }

    /**
     * @return array
     */
    public function getReviewTag(): array
    {
        return $this->crawler
            ->filterXPath('//div/div[2]/div[contains(@class, "tags")]/div')
            ->each(function (Crawler $crawler) {
                return JString::cleanse(
                    Parser::removeChildNodes($crawler)->text()
                );
            });
    }

    /**
     * @return bool
     */
    public function isPreliminary(): bool
    {
        $node = $this->crawler->filterXPath('//div/div[2]/div[contains(@class, "tags")]/div[contains(@class, "preliminary")]');

        if ($node->count()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isSpoiler(): bool
    {
        $node = $this->crawler->filterXPath('//div/div[2]/div[contains(@class, "tags")]/div[contains(@class, "spoiler")]');

        if ($node->count()) {
            return true;
        }

        return false;
    }
}
