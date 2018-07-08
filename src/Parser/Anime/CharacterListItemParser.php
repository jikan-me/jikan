<?php

namespace Jikan\Parser\Anime;

use Jikan\Helper\Parser;
use Jikan\Model\CharacterListItem;
use Jikan\Model\VoiceActor;
use Jikan\Parser\Character\VoiceActorParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CharacterListItemParser
 *
 * @package Jikan\Parser\Character
 */
class CharacterListItemParser implements ParserInterface
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
     * @return VoiceActor[]
     */
    public function getVoiceActors(): array
    {
        return $this->crawler->filterXPath('//table[2]/tr')->each(
            function (Crawler $c) {
                return (new VoiceActorParser($c))->getModel();
            }
        );
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        return Parser::idFromUrl($this->getCharacterUrl());
    }

    /**
     * @return string
     */
    public function getCharacterUrl(): string
    {
        return $this->crawler->filterXPath('//td[2]/a')->attr('href');
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->crawler->filterXPath('//td[2]/a')->text();
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->crawler->filterXPath('//img[1]')->attr('data-src');
    }

    /**
     * @return CharacterListItem
     */
    public function getModel(): CharacterListItem
    {
        return CharacterListItem::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getRole(): string
    {
        //echo $this->crawler->filterXPath('//td[2]/div/small')->text();
        return $this->crawler->filterXPath('//td[2]/div/small')->text();
    }
}
