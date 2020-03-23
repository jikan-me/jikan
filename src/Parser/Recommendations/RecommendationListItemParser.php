<?php

namespace Jikan\Parser\Recommendations;

use Jikan\Helper\Constants;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model;
use Jikan\Parser\Common\AnimeCardParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class RecommendationListItemParser
 *
 * @package Jikan\Parser
 */
class RecommendationListItemParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * RecommendationListItemParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return Model\Recommendations\RecommendationListItem
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Recommendations\RecommendationListItem
    {
        return Model\Recommendations\RecommendationListItem::fromParser($this);
    }

    public function getAnime(): array
    {
        return $this->crawler
            ->filterXPath('//table/tr/td')
            ->each(function (Crawler $crawler) {
                return new Model\Common\AnimeMeta(
                    $crawler->filterXPath('//a/strong')->text(),
                    $crawler->filterXPath('//a')->attr('href'),
                    $crawler->filterXPath('//div[1]/a/img')->attr('data-src')
                );
            });
    }

    public function getContent(): string
    {
        return $this->crawler
            ->filterXPath('//div[contains(@class, "recommendations-user-recs-text")]')->text();
    }

    public function getDate(): \DateTimeImmutable
    {
        $node = Parser::removeChildNodes(
            $this->crawler
                ->filterXPath('//div[contains(@class, "lightLink")]')
        );

        preg_match('~- (.*)$~', $node->text(), $time);

        return new \DateTimeImmutable($time[1]);
    }

    public function getRecommender(): Model\Recommendations\Recommender
    {
        return Model\Recommendations\Recommender::fromMeta(
            Constants::BASE_URL . $this->crawler->filterXPath('//div[contains(@class, "lightLink")]/a')->attr('href'),
            $this->crawler->filterXPath('//div[contains(@class, "lightLink")]/a')->text()
        );
    }
}
