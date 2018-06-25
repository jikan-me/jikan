<?php

namespace Jikan\Model;

use Jikan\Parser;

/**
 * Class Seasonal
 *
 * @package Jikan\Model
 */
class Seasonal extends Model
{

    /**
     * @var string
     */
    public $season;

    /**
     * @var array|SeasonalAnime[]
     */
    public $anime = [];

    /**
     * @param Parser\Seasonal $parser
     *
     * @return Seasonal
     */
    public static function fromParser(Parser\Seasonal $parser): self
    {
        $instance = new self();
        $instance->season = $parser->getSeason();
        $instance->anime = $parser->getSeasonalAnime();

        return $instance;
    }

    /**
     * @return string
     */
    public function getSeason(): string
    {
        return $this->season;
    }

    /**
     * @return array|SeasonalAnime[]
     */
    public function getAnime(): array
    {
        return $this->anime;
    }
}