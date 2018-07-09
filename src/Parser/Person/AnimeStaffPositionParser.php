<?php

namespace Jikan\Parser\Person;

use Jikan\Helper\JString;
use Jikan\Model;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimeStaffPositionParser
 *
 * @package Jikan\Parser
 */
class AnimeStaffPositionParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * AnimeStaffPositionParser constructor.
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
    public function getModel(): Model\Person\AnimeStaffPosition
    {
        return Model\Person\AnimeStaffPosition::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getPosition(): string
    {
        return JString::cleanse(
            $this->crawler
                ->filterXPath('//small')
                ->text()
        );
    }

    /**
     * @return \Jikan\Model\Anime\AnimeMeta
     * @throws \InvalidArgumentException
     */
    public function getAnimeMeta(): Model\Anime\AnimeMeta
    {
        return new Model\Anime\AnimeMeta(
            $this->crawler->filterXPath('//td[position() = 2]/a')->text(),
            $this->crawler->filterXPath('//td[position() = 2]/a')->attr('href'),
            $this->crawler->filterXPath('//td[position() = 1]/div/a/img')->attr('data-src')
        );
    }
}
