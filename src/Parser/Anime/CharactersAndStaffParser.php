<?php

namespace Jikan\Parser\Anime;

use Jikan\Model\Anime\AnimeCharactersAndStaff;
use Jikan\Model\Anime\StaffListItem;
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
     * @return \Jikan\Model\Character\CharacterListItem[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getCharacters(): array
    {
        return $this->crawler
            ->filterXPath('//div[contains(@class, "anime-character-container")]/table')
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
    public function getModel(): AnimeCharactersAndStaff
    {
        return AnimeCharactersAndStaff::fromParser($this);
    }

    /**
     * @return StaffListItem[]
     * @throws \InvalidArgumentException
     */
    public function getStaff(): array
    {
        $node = $this->crawler
            ->filterXPath('//h2[text()="Staff"]')
            ->ancestors()->nextAll()
            ->reduce(
                function (Crawler $crawler) {
                    return (bool)$crawler->filterXPath(
                        '//a[contains(@href, "https://myanimelist.net/people")]'
                    )->count();
                }
            );

        return $node
            ->each(
                function (Crawler $crawler) {
                    return (new StaffListItemParser($crawler))->getModel();
                }
            );
    }
}
