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
        // The article's own info block is the first .information on the page.
        // Build the date text WITHOUT removing child nodes from the live DOM
        // (the old removeChildNodes call mutated the crawler, breaking any
        // later getter on the same parser instance).
        $info = $this->crawler->filterXPath('//div[contains(@class, "information")][1]');

        if (!$info->count()) {
            return null;
        }

        $html = preg_replace('~<a\b[^>]*>.*?</a>~is', '', $info->html());
        $html = preg_replace('~<b>.*?</b>~is', '', $html);
        $text = trim(preg_replace('~\s+~', ' ', strip_tags(str_replace('<br>', ' ', $html))));
        $text = trim(str_replace(['by ', 'removed_user', ' |', 'views'], '', $text));

        if ($text === '') {
            return null;
        }

        try {
            return new \DateTimeImmutable($text, new \DateTimeZone('UTC'));
        } catch (\Exception $e) {
            return null;
        }
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

        return new MalUrlParser($node)->getModel();
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
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getViews(): ?int
    {
        // Featured pages show the view count in the article's own info block;
        // pages without a count (or without info) resolve to null instead of
        // throwing or aggregating sibling blocks' counts.
        $views = $this->crawler
            ->filterXPath('//div[contains(@class, "information")][1]//b');

        if (!$views->count()) {
            return null;
        }

        return (int) str_replace(',', '', $views->text());
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
            return new TagUrlParser($crawler)->getModel();
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
                            return new MalUrlParser($c)->getModel();
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
            return new ArticleListItemParser($crawler)->getModel();
        });
    }
}
