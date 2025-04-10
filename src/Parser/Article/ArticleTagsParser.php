<?php

namespace Jikan\Parser\Article;

use Jikan\Parser\Common\MalUrlParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ArticleTagsParser
 *
 * @package Jikan\Parser
 */
class ArticleTagsParser implements ParserInterface
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

    public function getModel(): array
    {
        $node = $this->crawler
            ->filterXPath('
            //div[contains(@class, "news-tags-table")]
            //div[contains(@class, "tag-list-col")]
        ');

        if (!$node->count()) {
            return [];
        }

        $tags = $node
            ->each(
                function (Crawler $crawler) {

                    return $crawler
                        ->filterXPath('//div[contains(@class, "tag-list")]')
                        ->each(function (Crawler $crawler) {
                            return (new MalUrlParser(
                                $crawler->filterXPath('//a')
                            ))
                                ->getModel();
                        });
                }
            );

        return array_merge(...$tags);
    }
}
