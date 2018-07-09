<?php

namespace Jikan\Parser\News;

use Jikan\Helper\Constants;
use Jikan\Helper\Parser;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\News\NewsListItem;
use Jikan\Parser\Common\MalUrlParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class NewsListParser
 *
 * @package Jikan\Parser
 */
class NewsListItemParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * MangaParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return NewsListItem
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): NewsListItem
    {
        return NewsListItem::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getTitle(): string
    {
        return $this->crawler->filterXPath('//p/a/strong')->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return Constants::BASE_URL.$this->crawler->filterXPath('//p/a/strong/..')->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getImage(): string
    {
        return $this->crawler->filterXPath('//img[1]')->attr('data-src');
    }

    /**
     * @return \DateTimeImmutable
     * @throws \InvalidArgumentException
     */
    public function getDate(): ?\DateTimeImmutable
    {
        return Parser::parseDate(explode(' by', $this->crawler->filterXPath('//p[last()]')->text())[0]);
    }

    /**
     * @return MalUrl
     * @throws \InvalidArgumentException
     */
    public function getAuthor(): MalUrl
    {
        return (new MalUrlParser($this->crawler->filterXPath('//a[contains(@href, "profile")][1]')))->getModel();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getDiscussionLink(): string
    {
        return Constants::BASE_URL.$this->crawler->filterXPath('//a[last()]')->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getIntro(): string
    {
        return Parser::removeChildNodes($this->crawler->filterXPath('//p[2]'))->text();
    }
}
