<?php

namespace Jikan\Parser\Article;

use Jikan\Model\Article\ArticleList;
use Jikan\Parser\Article\ArticleListItemParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ArticleListParser
 *
 * @package Jikan\Parser
 */
class ArticleListParser implements ParserInterface
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


    public function getModel(): ArticleList
    {
        return ArticleList::fromParser($this);
    }


    public function getResults(): array
    {
        $node = $this->crawler
            ->filterXPath('
            //*[contains(@class, "news-list")]
            /div[contains(@class, "news-unit") and contains(@class, "clearfix")]
        ');

        if (!$node->count()) {
            return [];
        }

        return $node
            ->each(
                function (Crawler $crawler) {
                    return (new ArticleListItemParser($crawler))->getModel();
                }
            );
    }

    /**
     * @return bool
     */
    public function getHasNextPage(): bool
    {
        return false;
    }

    /**
     * @return int
     */
    public function getLastVisiblePage(): int
    {
        return 1;
    }
}
