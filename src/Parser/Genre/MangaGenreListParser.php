<?php

namespace Jikan\Parser\Genre;

use Jikan\Model;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class GenreParser
 *
 * @package Jikan\Parser
 */
class MangaGenreListParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * GenreParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return \Jikan\Model\Genre\MangaGenreList
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Genre\MangaGenreList
    {
        return Model\Genre\MangaGenreList::fromParser($this);
    }

    /**
     * @return array|\Jikan\Model\Genre\MangaGenreListItem[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getGenres(): array
    {
        return $this->crawler
            ->filterXPath('//*[@class="genre-link"][1]/div/div/a[@class="genre-name-link"]')
            ->each(function (Crawler $crawler) {
                return (new MangaGenreListItemParser($crawler))->getModel();
            });
    }

    /**
     * @return array|\Jikan\Model\Genre\MangaGenreListItem[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getExplicitGenres(): array
    {
        return $this->crawler
            ->filterXPath('//*[@class="genre-link"][2]/div/div/a[@class="genre-name-link"]')
            ->each(function (Crawler $crawler) {
                return (new MangaGenreListItemParser($crawler))->getModel();
            });
    }

    /**
     * @return array|\Jikan\Model\Genre\MangaGenreListItem[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getThemes(): array
    {
        return $this->crawler
            ->filterXPath('//*[@class="genre-link"][3]/div/div/a[@class="genre-name-link"]')
            ->each(function (Crawler $crawler) {
                return (new MangaGenreListItemParser($crawler))->getModel();
            });
    }

    /**
     * @return array|\Jikan\Model\Genre\MangaGenreListItem[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getDemographics(): array
    {
        return $this->crawler
            ->filterXPath('//*[@class="genre-link"][4]/div/div/a[@class="genre-name-link"]')
            ->each(function (Crawler $crawler) {
                return (new MangaGenreListItemParser($crawler))->getModel();
            });
    }
}
