<?php

namespace Jikan\Parser\Manga;

use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MangaReviewsParser
 *
 * @package Jikan\Parser
 */
class MangaReviewsParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * MangaReviewsParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return array
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): array
    {
        return $this->crawler
            ->filterXPath('//div[@class="borderDark"]')
            ->each(function (Crawler $c) {
                return (new MangaReviewParser($c))->getModel();
            });
    }
}
