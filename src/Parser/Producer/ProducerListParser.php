<?php

namespace Jikan\Parser\Producer;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model;
use Jikan\Parser\Common\AnimeCardParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ProducerParser
 *
 * @package Jikan\Parser
 */
class ProducerListParser implements ParserInterface
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
     * @return \Jikan\Model\Producer\Producer
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Producer\ProducerList
    {
        return Model\Producer\ProducerList::fromParser($this);
    }

    /**
     * @return array|\Jikan\Model\Producer\ProducerAnime[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getProducers(): array
    {
        return $this->crawler
            ->filter('a.genre-name-link')
            ->each(
                function (Crawler $crawler) {
                    return (new ProducerListItemParser($crawler))->getModel();
                }
            );
    }
}
