<?php

namespace Jikan\Parser\Character;

use Jikan\Helper;
use Jikan\Model;
use Jikan\Parser\Common\MalUrlParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class VoiceActorParser
 *
 * @package Jikan\Parser
 */
class VoiceActorParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * VoiceActorParser constructor.
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
     * @return Model\VoiceActor
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\VoiceActor
    {
        return Model\VoiceActor::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return $this->crawler->filterXPath('//td[2]/a')->attr('href');
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getMalId(): int
    {
        return Helper\Parser::idFromUrl($this->getUrl());
    }

    /**
     * @return Model\MalUrl
     * @throws \InvalidArgumentException
     */
    public function getPerson(): Model\MalUrl
    {
        return (new MalUrlParser($this->crawler->filterXPath('//td[2]/a')))->getModel();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getImage(): string
    {
        return $this->crawler->filterXPath('//td[1]/div/a/img')->attr('src');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getLanguage(): string
    {
        return $this->crawler->filterXPath('//small')->last()->text();
    }
}
