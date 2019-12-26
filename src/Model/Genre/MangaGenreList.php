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

        return $instance;
    }

    /**
     * @return array|MangaGenreListItem[]
     */
    public function getGenres()
    {
        return $this->genres;
    }
}
