<?php

namespace Jikan\Parser\User\Profile;

use Jikan\Helper\Constants;
use Jikan\Helper\Parser;
use Jikan\Model\Common\CharacterMeta;
use Jikan\Model\User\FavoriteAnime;
use Jikan\Model\User\FavoriteCharacter;
use Jikan\Model\User\FavoriteManga;
use Jikan\Model\Common\PersonMeta;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\User\Favorites;
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
        return $this->crawler->filterXPath('//div[@id=\'anime_favorites\']/div[@class=\'fav-slide-outer\']/ul/li')
            ->each(
                function (Crawler $crawler) {
                    return new FavoriteAnime(
                        $crawler->filterXPath('//a/span[contains(@class, \'title\')]')->text(),
                        $crawler->filterXPath('//a')->attr('href'),
                        Parser::parseImageQuality($crawler->filterXPath('//a/img')
                            ->attr('data-src')),
                        $crawler->filterXPath('//a/span[contains(@class, \'users\')]')->text()
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
        return $this->crawler->filterXPath('//div[@id=\'manga_favorites\']/div[@class=\'fav-slide-outer\']/ul/li')
            ->each(
                function (Crawler $crawler) {
                    return new FavoriteManga(
                        $crawler->filterXPath('//a/span[contains(@class, \'title\')]')->text(),
                        $crawler->filterXPath('//a')->attr('href'),
                        Parser::parseImageQuality($crawler->filterXPath('//a/img')
                            ->attr('data-src')),
                        $crawler->filterXPath('//a/span[contains(@class, \'users\')]')->text()
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
        return $this->crawler->filterXPath('//div[@id=\'character_favorites\']/div[@class=\'fav-slide-outer\']/ul/li')
            ->each(
                function (Crawler $crawler) {
                    return new CharacterMeta(
                        $crawler->filterXPath('//a/span[contains(@class, \'title\')]')->text(),
                        Constants::BASE_URL.$crawler->filterXPath('//a')->attr('href'),
                        Parser::parseImageQuality($crawler->filterXPath('//a/img')
                            ->attr('data-src')),
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
        return $this->crawler->filterXPath('//div[@id=\'person_favorites\']/div[@class=\'fav-slide-outer\']/ul/li')
            ->each(
                function (Crawler $crawler) {
                    return new PersonMeta(
                        $crawler->filterXPath('//a/span[contains(@class, \'title\')]')->text(),
                        Constants::BASE_URL.$crawler->filterXPath('//a')->attr('href'),
                        Parser::parseImageQuality($crawler->filterXPath('//a/img')
                            ->attr('data-src')),
                    );
                }
            );
    }
}
