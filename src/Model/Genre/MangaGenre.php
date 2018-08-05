<?php

namespace Jikan\Model\Genre;

use Jikan\Parser\Genre\MangaGenreParser;

/**
 * Class MangaGenre
 *
 * @package Jikan\Model
 */
class MangaGenre
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
        $instance->malId = $parser->getMalId();
        $instance->name = $parser->getName();
        $instance->count = $parser->getCount();
        $instance->manga = $parser->getGenreManga();

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
     * @return array|MangaGenre[]
     */
    public function getManga(): array
    {
        return $this->manga;
    }
}
