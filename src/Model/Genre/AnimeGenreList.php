<?php

namespace Jikan\Model\Genre;

use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Genre\AnimeGenreListParser;
use Jikan\Parser\Genre\AnimeGenreParser;

/**
 * Class AnimeGenre
 *
 * @package Jikan\Model
 */
class AnimeGenreList
{
    /**
     * @var array|AnimeGenreListItem[]
     */
    public $genres = [];

    /**
     * @var array|AnimeGenreListItem[]
     */
    public $explicitGenres = [];

    /**
     * @var array|AnimeGenreListItem[]
     */
    public $themes = [];

    /**
     * @var array|AnimeGenreListItem[]
     */
    public $demographics = [];


    /**
     * @param AnimeGenreListParser $parser
     *
     * @return AnimeGenre
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function fromParser(AnimeGenreListParser $parser): self
    {
        $instance = new self();

        $instance->genres = $parser->getGenres();
        $instance->explicitGenres = $parser->getExplicitGenres();
        $instance->themes = $parser->getThemes();
        $instance->demographics = $parser->getDemographics();

        return $instance;
    }

    /**
     * @return array|AnimeGenreListItem[]
     */
    public function getExplicitGenres(): array
    {
        return $this->explicitGenres;
    }

    /**
     * @return array|AnimeGenreListItem[]
     */
    public function getThemes(): array
    {
        return $this->themes;
    }

    /**
     * @return array|AnimeGenreListItem[]
     */
    public function getDemographics(): array
    {
        return $this->demographics;
    }

    /**
     * @return array|AnimeGenreListItem[]
     */
    public function getGenres()
    {
        return $this->genres;
    }
}
