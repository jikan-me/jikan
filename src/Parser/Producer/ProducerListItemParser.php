<?php

namespace Jikan\Parser\Producer;

use Jikan\Helper\Constants;
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
class ProducerListItemParser implements ParserInterface
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
     * @return \Jikan\Model\Producer\ProducerListItem
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Producer\ProducerListItem
    {
        return Model\Producer\ProducerListItem::fromParser($this);
    }

    /**
     * @return int|null
     */
    public function getMalId() : ?int
    {
        preg_match('~(\d+)/.*$~', $this->getUrl(), $matches);

        if (!empty($matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getUrl(): string
    {
        return Constants::BASE_URL . $this->crawler->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getName(): string
    {
        preg_match('~(.+)\s\(.*\)~', $this->crawler->text(), $node);

        return $node[1];
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getCount(): int
    {
        preg_match('~.+\s\((.+)\)~', $this->crawler->text(), $node);

        if ($node[1] === '-') {
            return 0;
        }

        $count = str_replace(',', '', $node[1]);

        return $count;
    }
}
