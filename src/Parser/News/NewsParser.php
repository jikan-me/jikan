<?php

namespace Jikan\Parser\News;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\News\ResourceNews;
use Jikan\Parser\Common\MalUrlParser;
use Jikan\Parser\Common\TagUrlParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class NewsListParser
 *
 * @package Jikan\Parser
 */
class NewsParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private Crawler $crawler;

    /**
     * NewsListParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }


    /**
     * @return ResourceNews
     */
    public function getModel(): ResourceNews
    {
        return ResourceNews::fromParser($this);
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getMalId(): int
    {
        return Parser::stringIdFromUrl($this->getURL());
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getURL(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:url\']')->attr('content');
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:title\']')->attr('content');
    }

    /**
     * @return \DateTimeImmutable
     * @throws \InvalidArgumentException
     */
    public function getDate(): ?\DateTimeImmutable
    {
        return Parser::parseDate(
            $this->crawler
                ->filterXPath('//div[contains(@class, "news-container")]//a[contains(@class, "comment")]')
                ->text()
        );
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
        return $this->crawler->filterXPath('//a[last()]')->attr('href');
    }


    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return Parser::parseImageQuality(
            $this->crawler
                ->filterXPath('//meta[@property=\'og:image\']')
                ->attr('content')
        );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getComments(): int
    {
        $comments = $this->crawler->filterXPath('//a[last()]')->text();
        preg_match('~Discuss \((\d+) comments\)~', $comments, $comments);
        return !empty($comments) ? $comments[1] : 0;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        $node = $this->crawler
            ->filterXPath('//div[contains(@class, "tags")]');

        if (!$node->count()) {
            return [];
        }

        return $node->each(function (Crawler $crawler) {
            return (new TagUrlParser($crawler))->getModel();
        });
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->crawler
            ->filterXPath('//div[contains(@class, "content")]')
            ->html();
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getRelated(): array
    {
        $related = [];
        $this->crawler
            ->filterXPath('//table[contains(@class, "news-related-database")]/tr')
            ->each(
                function (Crawler $c) use (&$related) {
                    $links = $c->filterXPath('//td[2]/a');
                    $relation = JString::cleanse(
                        str_replace(':', '', $c->filterXPath('//td[1]')->text())
                    );

                    if ($links->count() === 1 // if it's the only link MAL has listed
                        && empty($links->first()->text()) // and if its a bugged/empty link
                    ) {
                        $related[$relation] = [];
                        return;
                    }

                    // Remove empty/bugged links #justMALThings
                    foreach ($links as $node) {
                        if (empty($node->textContent)) {
                            $node->parentNode->removeChild($node);
                        }
                    }

                    $related[$relation] = $links->each(
                        function (Crawler $c) {
                            return (new MalUrlParser($c))->getModel();
                        }
                    );
                }
            );

        return $related;
    }
}
