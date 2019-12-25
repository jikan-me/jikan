<?php

namespace Jikan\Parser\Magazine;

use Jikan\Model;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MagazineParser
 *
 * @package Jikan\Parser
 */
class MagazineListParser implements ParserInterface
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
     * @return \Jikan\Model\Magazine\Magazine
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Magazine\MagazineList
    {
        return Model\Magazine\MagazineList::fromParser($this);
    }

    /**
     * @return array|\Jikan\Model\Magazine\MagazineAnime[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getMagazines(): array
    {
        return $this->crawler
            ->filter('a.genre-name-link')
            ->each(
                function (Crawler $crawler) {
                    return (new MagazineListItemParser($crawler))->getModel();
                }
            );
    }
}
