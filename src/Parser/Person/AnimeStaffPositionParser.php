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
        $role = $this->crawler
            ->filterXPath('//td[2]/div[2]');

        return JString::cleanse(
            $role->text()
        );
    }

    /**
     * @return \Jikan\Model\Common\AnimeMeta
     * @throws \InvalidArgumentException
     */
    public function getAnimeMeta(): Model\Common\AnimeMeta
    {
        return new Model\Common\AnimeMeta(
            $this->crawler->filterXPath('//td[position() = 2]/div/a')->text(),
            $this->crawler->filterXPath('//td[position() = 2]/div/a')->attr('href'),
            $this->crawler->filterXPath('//td[position() = 1]/div/a/img')->attr('data-src')
        );
    }
}
