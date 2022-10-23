<?php

namespace Jikan\Model\User;

use Jikan\Parser\User\Profile\FavoritesParser;

/**
 * Class Favorites
 *
 * @package Jikan\Model
 */
class Favorites
{
    /**
     * @var array
     */
    private $anime;

    /**
     * @var array
     */
    private $manga;

    /**
     * @var array
     */
    private $characters;

    /**
     * @var array
     */
    private $people;

    /**
     * @param FavoritesParser $parser
     *
     * @return Favorites
     * @throws \InvalidArgumentException
     */
    public static function fromParser(FavoritesParser $parser): Favorites
    {
        $instance = new self();
        $instance->anime = $parser->getAnime();
        $instance->manga = $parser->getManga();
        $instance->characters = $parser->getCharacters();
        $instance->people = $parser->getPeople();

        return $instance;
    }

    /**
     * @return array
     */
    public function getAnime(): array
    {
        return $this->anime;
    }

    /**
     * @return array
     */
    public function getManga(): array
    {
        return $this->manga;
    }

    /**
     * @return array
     */
    public function getCharacters(): array
    {
        return $this->characters;
    }

    /**
     * @return array
     */
    public function getPeople(): array
    {
        return $this->people;
    }
}
