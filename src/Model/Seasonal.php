<?php

namespace Jikan\Model;

use Jikan\Parser\Seasonal\SeasonalParser;

/**
 * Class Seasonal
 *
 * @package Jikan\Model
 */
class Seasonal
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
     * @param SeasonalParser $parser
     *
     * @return Seasonal
     */
    public static function fromParser(SeasonalParser $parser): self
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
