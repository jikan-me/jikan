<?php

namespace Jikan\Parser\Manga;

use Jikan\Model\Manga\CharacterListItem;
use Jikan\Parser\Character\CharacterListItemParser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CharactersAndStaffParser
 *
 * @package Jikan\Parser
 */
class CharactersParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * AnimeParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return CharacterListItem[]
     */
    public function getCharacters(): array
    {
        return $this->crawler
            ->filterXPath('//h2[text()="Characters"]/following-sibling::table')
            ->reduce(
                function (Crawler $crawler) {
                    return (bool)$crawler->filterXPath(
                        '//a[contains(@href, "https://myanimelist.net/character")]'
                    )->count();
                }
            )
            ->each(
                function (Crawler $crawler) {
                    return \Jikan\Model\Manga\CharacterListItem::fromParser(new CharacterListItemParser($crawler));
                }
            );
    }
}
