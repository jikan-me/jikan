<?php

namespace Jikan\Parser\Producer;

use Jikan\Helper\JString;
use Jikan\Model;
use Jikan\Parser\Common\AnimeCardParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ProducerParser
 *
 * @package Jikan\Parser
 */
class ProducerParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * ProducerParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return Model\Producer
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Producer
    {
        return Model\Producer::fromParser($this);
    }

    /**
     * @return array|Model\ProducerAnime[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getProducerAnime(): array
    {
        return $this->crawler
            ->filter('div.seasonal-anime')
            ->each(
                function (Crawler $animeCrawler) {
                    return (new AnimeCardParser($animeCrawler))->getModel();
                }
            );
    }

    /**
     * @return string
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return $this->crawler->filterXPath('//meta[@property="og:url"]')->attr('content');
    }
}
