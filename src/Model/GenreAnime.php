<?php

namespace Jikan\Model;

use Jikan\Parser;

/**
 * Class GenreAnime
 *
 * @package Jikan\Model
 */
class GenreAnime extends AnimeCard
{

    /**
     * @param Parser\Common\AnimeCardParser $parser
     *
     * @return GenreAnime
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function parseGenreAnime(Parser\Common\AnimeCardParser $parser): GenreAnime
    {
        $instance = new self();
        parent::setProperties($parser, $instance);

        return $instance;
    }
}
