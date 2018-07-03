<?php


namespace Jikan\Parser\Anime;

use Jikan\Helper\Parser;
use Jikan\Model\StaffListItem;
use Jikan\Parser\Common\MalUrlParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class StaffListItemParser
 *
 * @package Jikan\Parser\Anime
 */
class StaffListItemParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * StaffListItemParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return string[]
     */
    public function getPositions(): array
    {
        return explode(', ', $this->crawler->filterXPath('//small')->text());
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        return Parser::idFromUrl($this->getUrl());
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->getMalUrl()->getUrl();
    }

    /**
     * @return \Jikan\Model\MalUrl
     */
    public function getMalUrl(): \Jikan\Model\MalUrl
    {
        $link = $this->crawler->filterXPath('//a')
            ->reduce(
                function (Crawler $c) {
                    return !$c->filterXPath('//img')->count();
                }
            )
            ->first();

        return (new MalUrlParser($link))->getModel();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getMalUrl()->getName();
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->crawler->filterXPath('//img')->attr('data-src');
    }

    /**
     * Return the model
     */
    public function getModel(): StaffListItem
    {
        return StaffListItem::fromParser($this);
    }
}
