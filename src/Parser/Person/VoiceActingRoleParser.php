<?php

namespace Jikan\Parser\Person;

use Jikan\Helper\JString;
use Jikan\Model;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

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
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getModel(): Model\Person\VoiceActingRole
    {
        return Model\Person\VoiceActingRole::fromParser($this);
    }


    /**
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getRole(): ?string
    {
        $role = $this->crawler
            ->filterXPath('//td[3]/div')
            ->text();

        return JString::UTF8NbspTrim(
            JString::cleanse(
                $role
            )
        );
    }

    /**
     * @return \Jikan\Model\Common\AnimeMeta
     * @throws \InvalidArgumentException
     */
    public function getAnimeMeta(): Model\Common\AnimeMeta
    {
        return new Model\Common\AnimeMeta(
            $this->crawler->filterXPath('//td[position() = 2]/a')->text(),
            $this->crawler->filterXPath('//td[position() = 2]/a')->attr('href'),
            $this->crawler->filterXPath('//td[position() = 1]/div/a/img')->attr('data-src')
        );
    }


    /**
     * @return \Jikan\Model\Common\CharacterMeta
     * @throws \InvalidArgumentException
     */
    public function getCharacterMeta(): Model\Common\CharacterMeta
    {
        return new Model\Common\CharacterMeta(
            $this->crawler->filterXPath('//td[position() = 3]/a')->text(),
            $this->crawler->filterXPath('//td[position() = 3]/a')->attr('href'),
            $this->crawler->filterXPath('//td[position() = 4]/div/a/img')->attr('data-src')
        );
    }
}
