<?php

namespace Jikan\Model\Seasonal;

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
    public $season_name;

    /**
     * @var int
     */
    public $season_year;

    /**
     * @var array|SeasonalAnime[]
     */
    public $anime = [];

    /**
     * @param SeasonalParser $parser
     *
     * @return Seasonal
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function fromParser(SeasonalParser $parser): self
    {
        $instance = new self();
        $instance->season_name = explode(" ", $parser->getSeason())[0];
        $instance->season_year = (int) explode(" ", $parser->getSeason())[1];
        $instance->anime = $parser->getSeasonalAnime();

        return $instance;
    }

    /**
     * @return string
     */
    public function getSeason(): string
    {
        return $this->season_name . " " . $this->season_year;
    }

    /**
     * @return int
     */
    public function getSeasonYear(): int
    {
        return $this->season_year;
    }

    /**
     * @return string
     */
    public function getSeasonName(): string
    {
        return $this->season_name;
    }

    /**
     * @return array|SeasonalAnime[]
     */
    public function getAnime(): array
    {
        return $this->anime;
    }
}
