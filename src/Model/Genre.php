<?php

namespace Jikan\Model;

use Jikan\Parser\Genre\GenreParser;

/**
 * Class Genre
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
     * @var array|GenreAnime[]
     */
    public $anime = [];

    /**
     * @param GenreParser $parser
     *
     * @return Genre
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function fromParser(GenreParser $parser): self
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
     * @return array|GenreAnime[]
     */
    public function getAnime(): array
    {
        return $this->anime;
    }
}
