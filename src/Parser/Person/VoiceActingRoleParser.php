<?php

namespace Jikan\Parser\Person;

use Jikan\Helper\JString;
use Jikan\Model;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;
use Jikan\Parser\Common\AnimeMetaParser;
use Jikan\Parser\Common\CharacterMetaParser;


/**
 * Class VoiceActingRoleParser
 *
 * @package Jikan\Parser
 */
class VoiceActingRoleParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * VoiceActingRoleParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * Return the model
     */
    public function getModel(): Model\VoiceActingRole
    {
        return Model\VoiceActingRole::fromParser($this);
    }


    /**
     * @return string
     * @throws \RuntimeException
     */
    public function getRole(): string
    {
        return Jstring::cleanse(
            $this->crawler
                ->filterXPath('//td[3]/div')
                ->text()
        );
    }

    /**
     * @return Model\AnimeMeta[]
     * @throws \InvalidArgumentException
     */
    public function getAnimeMeta(): Model\AnimeMeta
    {
        return (new AnimeMetaParser(
            $this->crawler->filterXPath('//td[3]')->previousAll()
        ))->getModel();
    }


    /**
     * @return Model\CharacterMeta[]
     * @throws \InvalidArgumentException
     */
    public function getCharacterMeta(): Model\CharacterMeta
    {
        return (new CharacterMetaParser(
            $this->crawler->filterXPath('//td[2]')->nextAll()
        ))->getModel();
    }

}
