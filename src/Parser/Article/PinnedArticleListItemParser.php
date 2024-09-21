<?php

namespace Jikan\Parser\Article;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Article\ArticleListItem;
use Jikan\Model\Article\PinnedArticleListItem;
use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Common\MalUrlParser;
use Jikan\Parser\Common\TagUrlParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class PinnedArticleListItemParser
 *
 * @package Jikan\Parser
 */
class PinnedArticleListItemParser implements ParserInterface
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
     * @return PinnedArticleListItem
     */
    public function getModel(): PinnedArticleListItem
    {
        return PinnedArticleListItem::fromParser($this);
    }


    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->crawler
            ->filterXPath('//a[contains(@class,"title")]')
            ->text();
    }

    /**
     * @return int|null
     */
    public function getMalId(): ?int
    {
        return Parser::idFromUrl($this->getUrl());
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return $this->crawler
            ->filterXPath('//a[contains(@class,"title")]')
            ->attr('href');
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getImageUrl(): ?string
    {
        $imageUrl = $this->crawler->filterXPath('//a[contains(@class, "image")]');

        if (!$imageUrl->count()) {
            return null;
        }

        return Parser::parseImageQuality(
            $imageUrl->attr('data-bg')
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
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getViews(): int
    {
        $views = $this->crawler
            ->filterXPath('
                //div[contains(@class, "information")]/p[contains(@class, "di-tc")]//b
            ')
            ->text();

        return (int) str_replace(',', '', $views);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getExcerpt(): string
    {
        return JString::cleanse(
            $this->crawler
                ->filterXPath('//p[contains(@class, "text")]')
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
                //div[contains(@class, "tags-pickup-inner")]
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
