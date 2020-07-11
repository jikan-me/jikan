<?php

namespace Jikan\Parser\Top;

use Jikan\Model\Top\TopPerson;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TopPeoplesParser
 *
 * @package Jikan\Parser\Top
 */
class TopPeopleParser
{
    /**
     * @var Crawler
     */
    private $crawler;


    /**
     * CharacterListItemParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return TopPerson[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getTopPeople(): array
    {
        return $this->crawler
            ->filterXPath('//tr[@class="ranking-list"]')
            ->each(
                function (Crawler $crawler) {
                    return TopPerson::fromParser(new TopListItemParser($crawler));
                }
            );
    }
}
