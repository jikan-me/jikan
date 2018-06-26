<?php

namespace Jikan\Model;

use Jikan\Parser;

/**
 * Class Character
 *
 * @package Jikan\Model
 */
class Character
{
    /**
     * @var int
     */
    public $malId;

    /**
     * @var string
     */
    public $characterUrl;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $nameKanji;

    /**
     * @var array
     */
    public $nicknames = [];

    /**
     * @var string
     */
    public $about;

    /**
     * @var int
     */
    public $memberFavorites;

    /**
     * @var string
     */
    public $imageUrl;

    /**
     * @var array
     */
    public $animeography = [];

    /**
     * @var array
     */
    public $mangaography = [];

    /**
     * @var array
     */
    public $voice_actor = [];

    /**
     * @param Parser\Character $parser
     *
     * @return Character
     */
    public static function fromParser(Parser\Character $parser): self
    {
        $instance = new self();

        return $instance;
    }
}
