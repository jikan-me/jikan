<?php

namespace Jikan\Parser\Top;

use Jikan\Model\Top\TopCharacter;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TopCharactersParser
 *
 * @package Jikan\Parser\Top
 */
class TopCharactersParser
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
     * @return TopCharacter[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getTopCharacters(): array
    {
        return $this->crawler
            ->filterXPath('//tr[@class="ranking-list"]')
            ->each(
                function (Crawler $crawler) {
                    return TopCharacter::fromParser(new TopListItemParser($crawler));
                }
            );
    }
}
