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
     * @return \Jikan\Model\Character\VoiceActor
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Character\VoiceActor
    {
        return Model\Character\VoiceActor::fromParser($this);
    }

    /**
     * @return string
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getName(): string
    {
        return $this->crawler->filterXPath('//a')
            ->reduce(
                function (Crawler $crawler) {
                    return !$crawler->filter('img')->count();
                }
            )->text();
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
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return $this->crawler->filterXPath('//a')->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getImage(): string
    {
        $img = $this->crawler->filterXPath('//img');

        return Helper\Parser::parseImageQuality(
            $img->attr('src') ?? $img->attr('data-src')
        );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getLanguage(): string
    {
        $node = $this->crawler->filterXPath('//div[contains(@class, "js-anime-character-language")]');

        if ($node->count()) {
            return Helper\JString::UTF8NbspTrim(
                Helper\JString::cleanse(
                    $node->text()
                )
            );
        }


        return Helper\JString::UTF8NbspTrim(
            Helper\JString::cleanse(
                $this->crawler->filterXPath('//div/small')->text()
            )
        );
    }

    /**
     * @return \Jikan\Model\Common\MalUrl
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getPerson(): Model\Common\MalUrl
    {
        return (new MalUrlParser(
            $this->crawler->filterXPath('//a')
                ->reduce(
                    function (Crawler $crawler) {
                        return !$crawler->filter('img')->count();
                    }
                )
        ))->getModel();
    }

    /**
     * @return \Jikan\Model\Common\PersonMeta
     * @throws \InvalidArgumentException
     */
    public function getPersonMeta(): Model\Common\PersonMeta
    {
        return new Model\Common\PersonMeta(
            $this->getName(),
            $this->getUrl(),
            $this->getImage()
        );
    }
}
