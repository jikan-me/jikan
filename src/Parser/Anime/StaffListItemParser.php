<?php


namespace Jikan\Parser\Anime;

use Jikan\Helper\Parser;
use Jikan\Model\Anime\StaffListItem;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\Common\PersonMeta;
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
     * @throws \InvalidArgumentException
     */
    public function getPositions(): array
    {
        return explode(', ', $this->crawler->filterXPath('//small')->text());
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getMalId(): int
    {
        return Parser::idFromUrl($this->getUrl());
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return $this->getMalUrl()->getUrl();
    }

    /**
     * @return \Jikan\Model\Common\MalUrl
     * @throws \InvalidArgumentException
     */
    public function getMalUrl(): MalUrl
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
     * @throws \InvalidArgumentException
     */
    public function getName(): string
    {
        return $this->getMalUrl()->getName();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getImage(): string
    {
        return Parser::parseImageQuality(
            $this->crawler->filterXPath('//img')->attr('data-src')
        );
    }

    /**
     * Return the model
     *
     * @throws \InvalidArgumentException
     */
    public function getModel(): StaffListItem
    {
        return StaffListItem::fromParser($this);
    }

    /**
     * @return PersonMeta
     * @throws \InvalidArgumentException
     */
    public function getPersonMeta(): PersonMeta
    {
        return new PersonMeta(
            $this->getName(),
            $this->getUrl(),
            $this->getImage()
        );
    }
}
