<?php

namespace Jikan\Parser\News;

use Amp\Parallel\Context\ThreadContext;
use Jikan\Helper\Constants;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\News\NewsListItem;
use Jikan\Parser\Common\MalUrlParser;
use Jikan\Parser\Common\TagUrlParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class NewsListItemParser
 *
 * @package Jikan\Parser
 */
class NewsListItemParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private Crawler $crawler;

    /**
     * NewsListItemParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }


    /**
     * @return NewsListItem
     */
    public function getModel(): NewsListItem
    {
        return NewsListItem::fromParser($this);
    }


    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->crawler
            ->filterXPath('//p[contains(@class,"title")]/a')
            ->text();
    }

    /**
     * @return int|null
     */
    public function getMalId(): ?int
    {
        preg_match('~([\d]+)$~', $this->getUrl(), $matches);

        if (!empty($matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return $this->crawler
                ->filterXPath('//p[contains(@class,"title")]/a')
                ->attr('href');
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getImageUrl(): ?string
    {
        $image = $this->crawler->filterXPath('//*[contains(@class, "image-link")]/img');

        if (!$image->count()) {
            return null;
        }

        return Parser::parseImageQuality(
            $image->attr('src')
        );
    }

    /**
     * @return \DateTimeImmutable
     * @throws \InvalidArgumentException
     */
    public function getDate(): ?\DateTimeImmutable
    {
        return Parser::parseDate(
            explode(
                ' by',
                Parser::removeChildNodes(
                    $this->crawler
                        ->filterXPath('//div[contains(@class, "information")]/p')
                )->text()
            )[0]
        );
    }

    /**
     * @return MalUrl
     * @throws \InvalidArgumentException
     */
    public function getAuthor(): MalUrl
    {
        return (new MalUrlParser(
            $this->crawler
                ->filterXPath('//div[contains(@class, "information")]/p/a[1]')
        ))->getModel();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getDiscussionLink(): string
    {
        return $this->crawler
                ->filterXPath('//div[contains(@class, "information")]/p/a[last()]')
                ->attr('href');
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getComments(): int
    {
        $comments = $this->crawler
            ->filterXPath('
                //div[contains(@class, "information")]
                //a[contains(@class, "comment")]
            ')
            ->text();

        preg_match('~(\d+) Comments~', $comments, $comments);
        return !empty($comments) ? $comments[1] : 0;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getExcerpt(): string
    {
        return JString::cleanse(
            $this->crawler
                ->filterXPath('//div[contains(@class, "text")]')
                ->text()
        );
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        $node = $this->crawler
            ->filterXPath('
                //div[contains(@class, "information")]
                /p[contains(@class, "tags")]
                /a
            ');

        if (!$node->count()) {
            return [];
        }

        return $node->each(function (Crawler $crawler) {
            return (new TagUrlParser($crawler))->getModel();
        });
    }
}
