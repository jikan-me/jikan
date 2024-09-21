<?php

namespace Jikan\Parser\Article;

use Jikan\Model\Article\ArticleList;
use Jikan\Model\Article\PinnedArticleList;
use Jikan\Parser\Article\ArticleListItemParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class PinnedArticleListParser
 *
 * @package Jikan\Parser
 */
class PinnedArticleListParser implements ParserInterface
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


    public function getModel(): PinnedArticleList
    {
        return PinnedArticleList::fromParser($this);
    }


    public function getResults(): array
    {
        $node = $this->crawler
            ->filterXPath('
            //*[contains(@class, "featured-content-block-outer")]
            //div[contains(@class, "featured-pickup-unit")]
        ');

        if (!$node->count()) {
            return [];
        }

        return $node
            ->each(
                function (Crawler $crawler) {
                    return (new PinnedArticleListItemParser($crawler))->getModel();
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
