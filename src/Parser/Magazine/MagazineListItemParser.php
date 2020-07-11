<?php

namespace Jikan\Parser\Magazine;

use Jikan\Helper\Constants;
use Jikan\Model;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MagazineParser
 *
 * @package Jikan\Parser
 */
class MagazineListItemParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * MagazineParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return \Jikan\Model\Magazine\MagazineListItem
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Magazine\MagazineListItem
    {
        return Model\Magazine\MagazineListItem::fromParser($this);
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
        preg_match('~(.+)\s\(\d+\)~', $this->crawler->text(), $node);

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
        $count = str_replace(',', '', $node[1]);

        return $count;
    }
}
