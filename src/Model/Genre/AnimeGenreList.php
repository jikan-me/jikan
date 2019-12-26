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

        return $instance;
    }

    /**
     * @return array|AnimeGenreListItem[]
     */
    public function getGenres()
    {
        return $this->genres;
    }
}
