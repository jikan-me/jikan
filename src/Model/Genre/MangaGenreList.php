<?php

namespace Jikan\Model\Genre;

use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Genre\MangaGenreListParser;
use Jikan\Parser\Genre\MangaGenreParser;

/**
 * Class MangaGenre
 *
 * @package Jikan\Model
 */
class MangaGenreList
{
    /**
     * @var array|MangaGenreListItem[]
     */
    public $genres = [];

    /**
     * @var array|MangaGenreListItem[]
     */
    public $explicitGenres = [];

    /**
     * @var array|MangaGenreListItem[]
     */
    public $themes = [];

    /**
     * @var array|MangaGenreListItem[]
     */
    public $demographics = [];

    /**
     * @param MangaGenreListParser $parser
     *
     * @return MangaGenre
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function fromParser(MangaGenreListParser $parser): self
    {
        $instance = new self();

        $instance->genres = $parser->getGenres();
        $instance->explicitGenres = $parser->getExplicitGenres();
        $instance->themes = $parser->getThemes();
        $instance->demographics = $parser->getDemographics();

        return $instance;
    }

    /**
     * @return array|MangaGenreListItem[]
     */
    public function getExplicitGenres(): array
    {
        return $this->explicitGenres;
    }

    /**
     * @return array|MangaGenreListItem[]
     */
    public function getThemes(): array
    {
        return $this->themes;
    }

    /**
     * @return array|MangaGenreListItem[]
     */
    public function getDemographics(): array
    {
        return $this->demographics;
    }

    /**
     * @return array|MangaGenreListItem[]
     */
    public function getGenres()
    {
        return $this->genres;
    }
}
