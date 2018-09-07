<?php

namespace Jikan\Parser\Character;

use Jikan\Helper\Parser;
use Jikan\Model\Character\CharacterListItem;
use Jikan\Model\Character\VoiceActor;
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
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
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
     * @throws \InvalidArgumentException
     */
    public function getMalId(): int
    {
        return Parser::idFromUrl($this->getCharacterUrl());
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getCharacterUrl(): string
    {
        return $this->crawler->filterXPath('//td[2]/a')->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getName(): string
    {
        return $this->crawler->filterXPath('//td[2]/a')->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getImage(): string
    {
        return Parser::parseImageQuality(
            $this->crawler->filterXPath('//img[1]')->attr('data-src')
        );
    }

    /**
     * @return \Jikan\Model\Character\CharacterListItem
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
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
