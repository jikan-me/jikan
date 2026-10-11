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
class AnimeGenreListParser implements ParserInterface
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
     * @return \Jikan\Model\Genre\AnimeGenreList
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Genre\AnimeGenreList
    {
        return Model\Genre\AnimeGenreList::fromParser($this);
    }

    /**
     * The four genre sections (Genres / Explicit Genres / Themes / Demographics)
     * are the `genre-link` blocks whose links are genre URLs. MAL prepends
     * page-specific `genre-link` blocks before them (e.g. the "adapted from"
     * source filter on manga.php), so the sections are anchored by content, not
     * by block position.
     *
     * @return Crawler the four section blocks, in Genres…Demographics order
     */
    private function genreLinkSections(): Crawler
    {
        return $this->crawler
            ->filterXPath('//*[@class="genre-link"]')
            ->reduce(static function (Crawler $block): bool {
                return $block->filterXPath('.//a[contains(@href, "/genre/")]')->count() > 0;
            });
    }

    /**
     * @return array|\Jikan\Model\Genre\AnimeGenreListItem[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getGenres(): array
    {
        return $this->genreLinkSections()
            ->eq(0)
            ->filterXPath('.//a[@class="genre-name-link"]')
            ->each(function (Crawler $crawler) {
                return new AnimeGenreListItemParser($crawler)->getModel();
            });
    }

    /**
     * @return array|\Jikan\Model\Genre\AnimeGenreListItem[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getExplicitGenres(): array
    {
        return $this->genreLinkSections()
            ->eq(1)
            ->filterXPath('.//a[@class="genre-name-link"]')
            ->each(function (Crawler $crawler) {
                return new AnimeGenreListItemParser($crawler)->getModel();
            });
    }

    /**
     * @return array|\Jikan\Model\Genre\AnimeGenreListItem[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getThemes(): array
    {
        return $this->genreLinkSections()
            ->eq(2)
            ->filterXPath('.//a[@class="genre-name-link"]')
            ->each(function (Crawler $crawler) {
                return new AnimeGenreListItemParser($crawler)->getModel();
            });
    }

    /**
     * @return array|\Jikan\Model\Genre\AnimeGenreListItem[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getDemographics(): array
    {
        return $this->genreLinkSections()
            ->eq(3)
            ->filterXPath('.//a[@class="genre-name-link"]')
            ->each(function (Crawler $crawler) {
                return new AnimeGenreListItemParser($crawler)->getModel();
            });
    }
}
