<?php


namespace Jikan\Parser\Anime;

use Jikan\Helper\Parser;
use Jikan\Model\StaffListItem;
use Jikan\Parser\Common\MalUrlParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

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

    public function getMalUrl()
    {
        static $url;
        if ($url !== null) {
            return $url;
        }
        $link = $this->crawler->filterXPath('//a')
            ->reduce(
                function (Crawler $c) {
                    return !$c->filterXPath('//img')->count();
                }
            )
            ->first();

        $url = (new MalUrlParser($link))->getModel();

        return $url;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->getMalUrl()->getUrl();
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
    public function getModel()
    {
        return StaffListItem::fromParser($this);
    }
}
