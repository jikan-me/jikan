<?php

namespace Jikan\Parser\Anime;

use Jikan\Helper\Parser;
use Jikan\Model\Anime\MoreInfo;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MoreInfoParser
 *
 * @package Jikan\Parser\Anime
 */
class MoreInfoParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * MoreInfoParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * Return the model
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): MoreInfo
    {
        return MoreInfo::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getMoreInfo(): string
    {
        return trim(
            Parser::removeChildNodes(
                $this->crawler->filterXPath('//div[@class="js-scrollfix-bottom-rel"]')
            )->text()
        );
    }
}
