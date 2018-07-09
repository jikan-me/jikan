<?php

namespace Jikan\Model\Genre;

use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Genre\MangaGenreParser;

/**
 * Class MangaGenre
 *
 * @package Jikan\Model
 */
class MangaGenre
{

    /**
     * @var MalUrl
     */
    public $url;

    /**
     * @var array|MangaGenre[]
     */
    public $manga = [];

    /**
     * @param MangaGenreParser $parser
     *
     * @return MangaGenre
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function fromParser(MangaGenreParser $parser): self
    {
        $instance = new self();
        $instance->url = $parser->getUrl();
        $instance->manga = $parser->getGenreManga();

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
     * @return array|MangaGenre[]
     */
    public function getManga(): array
    {
        return $this->manga;
    }
}
