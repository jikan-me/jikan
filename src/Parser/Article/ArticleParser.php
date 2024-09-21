<?php

namespace Jikan\Parser\Article;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Article\ResourceArticle;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\News\ResourceNews;
use Jikan\Parser\Common\MalUrlParser;
use Jikan\Parser\Common\NewsMetaParser;
use Jikan\Parser\Common\TagUrlParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ArticleParser
 *
 * @package Jikan\Parser
 */
class ArticleParser implements ParserInterface
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
     * @return ResourceArticle
     */
    public function getModel(): ResourceArticle
    {
        return ResourceArticle::fromParser($this);
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getMalId(): int
    {
        return (int) Parser::idFromUrl($this->getURL());
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

    public function getExcerpt(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:description\']')->attr('content');

    }


    /**
     * @return \DateTimeImmutable|null
     * @throws \Exception
     */
    public function getDate(): ?\DateTimeImmutable
    {
        $date = $this->crawler
            ->filterXPath('//div[contains(@class, "information")]');

        $date = Parser::removeChildNodes($date, true)->text();

        $date = trim(str_replace(['by ', 'removed_user', ' |', 'views'], '', $date));

        return new \DateTimeImmutable(
            $date,
            new \DateTimeZone('UTC')
        );
    }

    /**
     * @return MalUrl
     * @throws \InvalidArgumentException
     */
    public function getAuthor(): MalUrl
    {
        $node = $this->crawler->filterXPath('
            //div[contains(@class, "information")]
            /a[contains(@href, "profile")][1]
        ');

        if (!$node->count()) {
            return new MalUrl('removed_user', null);
        }

        return (new MalUrlParser($node))->getModel();
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
    public function getViews(): int
    {
        $views = $this->crawler
            ->filterXPath('
                //div[contains(@class, "information")]
                //b
            ')
            ->text();


        return (int) str_replace(',', '', $views);
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        $node = $this->crawler
            ->filterXPath('
                //div[contains(@class, "news-container")]
                //div[contains(@class, "tags")]/a
            ');

        if (!$node->count()) {
            return [];
        }

        return $node->each(function (Crawler $crawler) use ($node) {
            return (new TagUrlParser($crawler))->getModel();
        });
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return trim(
            $this->crawler
                ->filterXPath('//div[contains(@class, "news-container")]//div[contains(@class, "content")]')
                ->html()
        );
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getRelatedEntries(): array
    {
        $related = [];
        $this->crawler
            ->filterXPath('
                //*[contains(text(), "Related Database Entries")]
                /following-sibling::table
                /tr
            ')
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

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getRelatedArticles(): array
    {
        $relatedArticles = $this->crawler
            ->filterXPath('
                //*[contains(text(), "Related Articles")]
                /following-sibling::div[contains(@class, "news-list")]
                /div[contains(@class, "news-unit")]
            ');

        if (!$relatedArticles->count()) {
            return [];
        }

        return $relatedArticles->each(function (Crawler $crawler) use ($relatedArticles) {
            return (new ArticleListItemParser($crawler))->getModel();
        });
    }
}
