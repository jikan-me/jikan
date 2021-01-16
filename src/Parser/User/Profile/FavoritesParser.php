<?php

namespace Jikan\Parser\User\Profile;

use Jikan\Helper\Constants;
use Jikan\Helper\Parser;
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
        return $this->crawler->filterXPath('//ul[@class=\'favorites-list anime\']/li')
            ->each(
                function (Crawler $crawler) {
                    return new FavoriteAnime(
                        $crawler->filterXPath('//div[position() = 2]/a')->text(),
                        $crawler->filterXPath('//div[position() = 2]/a')->attr('href'),
                        Parser::parseImageQuality($crawler->filterXPath('//div[position() = 1]/a/img')
                            ->attr('src')),
                        $crawler->filterXPath('//div[position() = 2]/span')->text()
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
                    return new FavoriteManga(
                        $crawler->filterXPath('//div[position() = 2]/a')->text(),
                        $crawler->filterXPath('//div[position() = 2]/a')->attr('href'),
                        Parser::parseImageQuality($crawler->filterXPath('//div[position() = 1]/a/img')
                            ->attr('src')),
                        $crawler->filterXPath('//div[position() = 2]/span')->text()
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
                    return new FavoriteCharacter(
                        $crawler->filterXPath('//div[position() = 2]/a')->text(),
                        $crawler->filterXPath('//div[position() = 2]/a')->attr('href'),
                        Parser::parseImageQuality($crawler->filterXPath('//div[position() = 1]/a/img')
                            ->attr('src')),
                        new MalUrl(
                            trim($crawler->filterXPath('//div[position() = 2]/span/a')->text()),
                            Constants::BASE_URL . $crawler->filterXPath('//div[position() = 2]/span/a')->attr('href')
                        )
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
                    return new PersonMeta(
                        $crawler->filterXPath('//div[position() = 2]/a')->text(),
                        $crawler->filterXPath('//div[position() = 2]/a')->attr('href'),
                        Parser::parseImageQuality($crawler->filterXPath('//div[position() = 1]/a/img')
                            ->attr('src'))
                    );
                }
            );
    }
}
