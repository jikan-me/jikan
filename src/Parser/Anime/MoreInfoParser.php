<?php

namespace Jikan\Parser\Anime;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Anime\AnimeMoreInfo;
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
     * MoreInfoParser Constructor
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
    public function getModel(): AnimeMoreInfo
    {
        return AnimeMoreInfo::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getMoreInfo(): ?string
    {
        $moreinfo = JString::cleanse(
            Parser::removeChildNodes(
                $this->crawler->filterXPath('//div[contains(@class, "rightside")]')
            )->text()
        );

        if (empty($moreinfo)) {
            return null;
        }

        return $moreinfo;
    }
}
