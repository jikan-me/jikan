<?php

namespace Jikan\Parser\User\History;

use Jikan\Model\User\History;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class HistoryParser
 *
 * @package Jikan\Parser\User\History
 */
class HistoryParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * HistoryParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return History[]
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function getModel(): array
    {
        $node = $this->crawler->filterXPath('//div[@id="content"]/div/table/tr');

        return $node
            ->reduce(
                function (Crawler $c) {
                    return (bool)$c->filterXPath('//td[contains(@class, "borderClass")]')->count();
                }
            )
            ->each(
                function (Crawler $c) {
                    return (new HistoryItemParser($c))->getModel();
                }
            );
    }
}
