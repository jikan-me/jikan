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
     * @return \Jikan\Model\Producer\Producer
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Producer\Producer
    {
        return Model\Producer\Producer::fromParser($this);
    }

    /**
     * @return array|\Jikan\Model\Producer\ProducerAnime[]
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
     * @return \Jikan\Model\Common\MalUrl
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getUrl(): Model\Common\MalUrl
    {
        return new Model\Common\MalUrl(
            JString::cleanse(
                Parser::removeChildNodes($this->crawler->filterXPath('//span[@class=\'di-ib mt4\']'))->text()
            ),
            $this->crawler->filterXPath('//meta[@property="og:url"]')->attr('content')
        );
    }
}
