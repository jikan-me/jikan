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
     * @var string|null
     */
    public $seasonName;

    /**
     * @var int|null
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
     * @return string|null
     */
    public function getSeasonName(): ?string
    {
        return $this->seasonName;
    }

    /**
     * @return int|null
     */
    public function getSeasonYear(): ?int
    {
        return $this->seasonYear;
    }

    /**
     * @return array|SeasonalAnime[]
     */
    public function getAnime()
    {
        return $this->anime;
    }
}
