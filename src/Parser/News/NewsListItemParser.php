<?php

namespace Jikan\Parser\News;

use Jikan\Helper\Constants;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\News\NewsListItem;
use Jikan\Model\News\ResourceNewsListItem;
use Jikan\Parser\Common\MalUrlParser;
use Jikan\Parser\ParserInterface;
use PHPUnit\Exception;
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


    public function getModel(): NewsListItem
    {
        return NewsListItem::fromParser($this);
    }


    public function getTitle(): string
    {
        return $this->crawler->filterXPath('//div[contains(@class,"news-unit-right")]/p/a')->text();
    }

    /**
     * @return int|null
     */
    public function getMalId() : ?int
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
        return Constants::BASE_URL.$this->crawler
                ->filterXPath('//div[contains(@class,"news-unit-right")]/p/a')
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
            explode(' by',
                Parser::removeChildNodes(
                    $this->crawler
                        ->filterXPath('//div[contains(@class,"news-unit-right")]/div[contains(@class, "information")]/p')
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
                ->filterXPath('//div[contains(@class,"news-unit-right")]/div[contains(@class, "information")]/p/a[1]')
        ))->getModel();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getDiscussionLink(): string
    {
        return Constants::BASE_URL.$this->crawler
                ->filterXPath('//div[contains(@class,"news-unit-right")]/div[contains(@class, "information")]/p/a[last()]')
                ->attr('href');
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getComments() : int
    {
        $comments = $this->crawler
            ->filterXPath('//div[contains(@class,"news-unit-right")]/div[contains(@class, "information")]/p/a[last()]')
            ->text();

        preg_match('~\((\d+) comments\)~', $comments, $comments);
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
                ->filterXPath('//div[contains(@class,"news-unit-right")]/div[contains(@class, "text")]')
            ->text()
        );
    }
}
