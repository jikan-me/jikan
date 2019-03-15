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
    public $seasonName;

    /**
     * @var int
     */
    public $seasonYear;

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
        $instance->seasonName = $parser->getSeasonName();
        $instance->seasonYear = $parser->getSeasonYear();
        $instance->anime = $parser->getSeasonalAnime();

        return $instance;
    }

    /**
     * @return string
     */
    public function getSeason(): string
    {
        return $this->seasonName . ' ' . $this->seasonYear;
    }

    /**
     * @return int
     */
    public function getSeasonYear(): int
    {
        return $this->seasonYear;
    }

    /**
     * @return string
     */
    public function getSeasonName(): string
    {
        return $this->seasonName;
    }

    /**
     * @return array|SeasonalAnime[]
     */
    public function getAnime(): array
    {
        return $this->anime;
    }
}
