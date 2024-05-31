<?php

namespace Jikan\Parser\News;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Common\TagMeta;
use Jikan\Model\News\NewsList;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class NewsTagsParser
 *
 * @package Jikan\Parser
 */
class NewsTagsParser implements ParserInterface
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
            //*[contains(@class, "content-left")]
            /div[contains(@class, "pt16")]
        ');

        if (!$node->count()) {
            return [];
        }

        $tags = $node
            ->each(
                function (Crawler $crawler) {
                    $type = $crawler
                        ->filterXPath('//h2[contains(@class, "news-tags-header")]')
                        ->text();

                    return $crawler
                        ->filterXPath('//table/tr')
                        ->each(function (Crawler $crawler) use ($type) {
                            return new TagMeta(
                                $crawler->filterXPath('//td[contains(@class, "tag-name")]/span/a')->text(),
                                $crawler->filterXPath('//td[contains(@class, "tag-name")]/span/a')->attr('href'),
                                $type,
                                JString::ifEmptyStringReturnNull($crawler->filterXPath('//td[contains(@class, "tag-description")]')->text()),
                            );
                        });
                }
            );

        return array_merge(...$tags);
    }
}
