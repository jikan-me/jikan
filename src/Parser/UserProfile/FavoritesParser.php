<?php

namespace Jikan\Parser\UserProfile;

use Jikan\Helper\Parser;
use Jikan\Model\Favorites;
use Jikan\Model\AnimeMeta;
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
                    return new AnimeMeta(
                        $crawler->filterXPath('//div[position() = 2]/a')->text(),
                        $crawler->filterXPath('//div[position() = 2]/a')->attr('href'),
                        $matches[1]
                    );
                }
            );
    }
}
