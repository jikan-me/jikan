<?php

namespace Jikan\Parser\UserProfile;

use Jikan\Model\Favorites;
use Jikan\Model\AnimeMeta;
use Jikan\Model\MangaMeta;
use Jikan\Model\CharacterMeta;
use Jikan\Model\PersonMeta;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Favorites
 *
 * @package Jikan\Parser
 */
class FavoritesParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * Favorites constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return Favorites
     * @throws \InvalidArgumentException
     */
    public function getModel(): Favorites
    {
        return Favorites::fromParser($this);
    }

 
    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getAnime(): array
    {
        return $this->crawler->filterXPath('//ul[@class=\'favorites-list anime\']/li')
            ->each(
                function (Crawler $crawler) {
                    preg_match(
                        '~background-image:url\(\'(.*)\'\)~',
                        $crawler->filterXPath('//div[position() = 1]/a')
                            ->attr('style'),
                        $matches
                    );
                    return new AnimeMeta(
                        $crawler->filterXPath('//div[position() = 2]/a')->text(),
                        $crawler->filterXPath('//div[position() = 2]/a')->attr('href'),
                        $matches[1]
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
        return $this->crawler->filterXPath('//ul[@class=\'favorites-list manga\']/li')
            ->each(
                function (Crawler $crawler) {
                    preg_match(
                        '~background-image:url\(\'(.*)\'\)~',
                        $crawler->filterXPath('//div[position() = 1]/a')
                            ->attr('style'),
                        $matches
                    );
                    return new MangaMeta(
                        $crawler->filterXPath('//div[position() = 2]/a')->text(),
                        $crawler->filterXPath('//div[position() = 2]/a')->attr('href'),
                        $matches[1]
                    );
                }
            );
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getCharacters(): array
    {
        return $this->crawler->filterXPath('//ul[@class=\'favorites-list characters\']/li')
            ->each(
                function (Crawler $crawler) {
                    preg_match(
                        '~background-image:url\(\'(.*)\'\)~',
                        $crawler->filterXPath('//div[position() = 1]/a')
                            ->attr('style'),
                        $matches
                    );
                    return new CharacterMeta(
                        $crawler->filterXPath('//div[position() = 2]/a')->text(),
                        $crawler->filterXPath('//div[position() = 2]/a')->attr('href'),
                        $matches[1]
                    );
                }
            );
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getPeople(): array
    {
        return $this->crawler->filterXPath('//ul[@class=\'favorites-list people\']/li')
            ->each(
                function (Crawler $crawler) {
                    preg_match(
                        '~background-image:url\(\'(.*)\'\)~',
                        $crawler->filterXPath('//div[position() = 1]/a')
                            ->attr('style'),
                        $matches
                    );
                    return new PersonMeta(
                        $crawler->filterXPath('//div[position() = 2]/a')->text(),
                        $crawler->filterXPath('//div[position() = 2]/a')->attr('href'),
                        $matches[1]
                    );
                }
            );
    }
}
