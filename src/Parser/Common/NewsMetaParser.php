<?php

namespace Jikan\Parser\Common;

use Jikan\Helper\Parser;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\Common\NewsMeta;
use Jikan\Parser\Common\MalUrlParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class NewsMetaParser
 *
 * @package Jikan\Parser
 */
class NewsMetaParser implements ParserInterface
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
     * @return NewsMeta
     */
    public function getModel(): NewsMeta
    {
        return NewsMeta::fromParser($this);
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
            ->filterXPath('//a[contains(@class,"title")]')
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
                        ->filterXPath('//span[contains(@class, "information")]')
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
                ->filterXPath('//span[contains(@class, "information")]/a[1]')
        ))->getModel();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getDiscussionLink(): string
    {
        return $this->crawler
            ->filterXPath('//a[contains(@class, "comment")]')
            ->attr('href');
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getComments(): int
    {
        $comments = $this->crawler
            ->filterXPath('//a[contains(@class, "comment")]')
            ->text();

        preg_match('~(\d+) Comments~', $comments, $comments);
        return !empty($comments) ? $comments[1] : 0;
    }
}
