<?php

namespace Jikan\Parser\News;

use Jikan\Helper\Constants;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\News\ResourceNewsListItem;
use Jikan\Parser\Common\MalUrlParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ResourceNewsListParser
 *
 * @package Jikan\Parser
 */
class ResourceNewsListItemParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private Crawler $crawler;

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
     * @return ResourceNewsListItem
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): ResourceNewsListItem
    {
        return ResourceNewsListItem::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getTitle(): string
    {
        return $this->crawler->filterXPath('//p[contains(@class, "title")]/a')->text();
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
        return $this->crawler->filterXPath('//p[contains(@class, "title")]/a')->attr('href');
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getImage(): ?string
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
            explode(' by', $this->crawler->filterXPath('//div[contains(@class, "information")]/p')->text())[0]
        );
    }

    /**
     * @return MalUrl
     * @throws \InvalidArgumentException
     */
    public function getAuthor(): MalUrl
    {
        return new MalUrlParser($this->crawler->filterXPath('//a[contains(@href, "profile")][1]'))->getModel();
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
            ->filterXPath('//div[contains(@class, "information")]//a[contains(@class, "comment")]')
            ->text();
        preg_match('~(\d+) Comments~', $comments, $comments);
        return !empty($comments) ? $comments[1] : 0;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getIntro(): string
    {
        return JString::cleanse(
            $this->crawler->filterXPath('//div[contains(@class, "text")]')->text()
        );
    }
}
