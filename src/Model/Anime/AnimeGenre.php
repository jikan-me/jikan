<?php

namespace Jikan\Model\Anime;

use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Genre\AnimeGenreParser;

/**
 * Class AnimeGenre
 *
 * @package Jikan\Model
 */
class AnimeGenre
{

    /**
     * @var \Jikan\Model\Common\MalUrl
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
     * @return \Jikan\Model\Common\MalUrl
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
