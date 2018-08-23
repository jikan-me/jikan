<?php

namespace Jikan\Model\Genre;

use Jikan\Model\Common\AnimeCard;
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
    public $malUrl;

    /**
     * @var int
     */
    public $itemCount;

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
        $instance->itemCount = $parser->getCount();
        $instance->anime = $parser->getGenreAnime();
        $instance->malUrl = new MalUrl(
            $parser->getName(),
            $parser->getUrl()
        );

        return $instance;
    }

    /**
     * @return \Jikan\Model\Common\MalUrl
     */
    public function getMalUrl(): MalUrl
    {
        return $this->malUrl;
    }

    /**
     * @return int
     */
    public function getItemCount(): int
    {
        return $this->itemCount;
    }

    /**
     * @return array|AnimeCard[]
     */
    public function getAnime(): array
    {
        return $this->anime;
    }
}
