<?php

namespace Jikan\Model;

use Jikan\Parser\Genre\AnimeGenreParser;

/**
 * Class AnimeGenre
 *
 * @package Jikan\Model
 */
class Genre
{

    /**
     * @var MalUrl
     */
    public $url;

    /**
     * @var array|AnimeCard[]
     */
    public $anime = [];

    /**
     * @param AnimeGenreParser $parser
     *
     * @return AnimeGenre
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function fromParser(AnimeGenreParser $parser): self
    {
        $instance = new self();
        $instance->url = $parser->getUrl();
        $instance->anime = $parser->getGenreAnime();

        return $instance;
    }

    /**
     * @return MalUrl
     */
    public function getUrl(): MalUrl
    {
        return $this->url;
    }

    /**
     * @return array|AnimeCard[]
     */
    public function getAnime(): array
    {
        return $this->anime;
    }
}
