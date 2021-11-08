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
            ->filterXPath('//td[3]/div[2]');

        if (!$role->count()) {
            return null;
        }

        return JString::UTF8NbspTrim(
            JString::cleanse(
                $role->text()
            )
        );
    }

    /**
     * @return \Jikan\Model\Common\AnimeMeta
     * @throws \InvalidArgumentException
     */
    public function getAnimeMeta(): Model\Common\AnimeMeta
    {
        $imageUrl = $this->crawler->filterXPath('//td[1]/div/a/img');
        $url = $this->crawler->filterXPath('//td[2]/div/a');

        return new Model\Common\AnimeMeta(
            $url->text(),
            $url->attr('href'),
            $imageUrl->attr('data-src')
        );
    }


    /**
     * @return \Jikan\Model\Common\CharacterMeta
     * @throws \InvalidArgumentException
     */
    public function getCharacterMeta(): Model\Common\CharacterMeta
    {
        $imageUrl = $this->crawler->filterXPath('//td[4]/div/a/img');
        $url = $this->crawler->filterXPath('//td[3]/div/a');

        return new Model\Common\CharacterMeta(
            $url->text(),
            $url->attr('href'),
            $imageUrl->attr('data-src')
        );
    }
}
