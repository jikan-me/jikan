<?php

namespace Jikan\Parser\Anime;

use Jikan\Model\CharacterListItem;
use Jikan\Model\CharactersAndStaff;
use Jikan\Model\StaffListItem;
use Jikan\Parser\Character\CharacterListItemParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CharactersAndStaffParser
 *
 * @package Jikan\Parser
 */
class CharactersAndStaffParser implements ParserInterface
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
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getCharacters(): array
    {
        return $this->crawler
            ->filterXPath('//h2[text()="Characters & Voice Actors"]/following-sibling::table')
            ->reduce(
                function (Crawler $crawler) {
                    return (bool)$crawler->filterXPath(
                        '//a[contains(@href, "https://myanimelist.net/character")]'
                    )->count();
                }
            )
            ->each(
                function (Crawler $crawler) {
                    return (new CharacterListItemParser($crawler))->getModel();
                }
            );
    }

    /**
     * Return the model
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): CharactersAndStaff
    {
        return CharactersAndStaff::fromParser($this);
    }

    /**
     * @return StaffListItem[]
     * @throws \InvalidArgumentException
     */
    public function getStaff(): array
    {
        return $this->crawler
            ->filterXPath('//h2/div/../following-sibling::table')
            ->each(
                function (Crawler $crawler) {
                    return (new StaffListItemParser($crawler))->getModel();
                }
            );
    }
}
