<?php

namespace Jikan\Model\Genre;

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
     * @var int
     */
    public $malId;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $count;

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
        $instance->malId = $parser->getMalId();
        $instance->name = $parser->getName();
        $instance->count = $parser->getCount();
        $instance->anime = $parser->getGenreAnime();

        return $instance;
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        return $this->malId;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return array|AnimeCard[]
     */
    public function getAnime(): array
    {
        return $this->anime;
    }
}
