<?php

namespace Jikan\Parser\Search;

use Jikan\Helper\Constants;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\Search\CharacterSearchListItem;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CharacterSearchListItemParser
 *
 * @package Jikan\Parser
 */
class CharacterSearchListItemParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * CharacterSearchParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return CharacterSearchListItem
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): CharacterSearchListItem
    {
        return CharacterSearchListItem::fromParser($this);
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
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getName(): string
    {
        return $this->crawler->filterXPath('//td[2]/a')->text();
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getAlternativeNames(): array
    {
        $names = $this->crawler->filterXPath('//td[2]/small');

        if (!$names->count()) {
            return [];
        }

        $names = str_replace(['(', ')'], '', $names->text());
        $names = explode(',', $names);

        foreach ($names as &$name) {
            $name = JString::cleanse($name);
        }

        return $names;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getImageUrl(): string
    {
        return Parser::parseImageQuality(
            $this->crawler->filterXPath('//td[1]/div/a/img')->attr('data-src')
        );
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getAnime(): array
    {
        $anime = Parser::removeChildNodes(
            $this->crawler
                ->filterXPath('//td[3]/small/a')
        );

        if (!$anime->count()) {
            return [];
        }

        return $anime->each(
            function (Crawler $c) {
                return new MalUrl(
                    $c->text(),
                    Constants::BASE_URL.$c->attr('href')
                );
            }
        );
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getManga(): array
    {
        $manga = $this->crawler
            ->filterXPath('//td[3]/small/div/a');

        if (!$manga->count()) {
            return [];
        }

        return $manga->each(
            function (Crawler $c) {
                return new MalUrl(
                    $c->text(),
                    Constants::BASE_URL.$c->attr('href')
                );
            }
        );
    }
}
