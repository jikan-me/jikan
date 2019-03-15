<?php

namespace Jikan\Parser\Anime;

use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimeReviewsParser
 *
 * @package Jikan\Parser
 */
class AnimeReviewsParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * AnimeReviewsParser constructor.
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
                return (new AnimeReviewParser($c))->getModel();
            });
    }
}
