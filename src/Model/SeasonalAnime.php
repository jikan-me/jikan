<?php

namespace Jikan\Model;

use Jikan\Parser;

/**
 * Class SeasonalAnime
 *
 * @package Jikan\Model
 */
class SeasonalAnime extends AnimeCard
{
    /**
     * @var bool
     */
    private $continueing;

    /**
     * @param Parser\AnimeCard $parser
     *
     * @return SeasonalAnime
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function parseSeasonalAnime(Parser\AnimeCard $parser): SeasonalAnime
    {
        $instance = new self();
        parent::setProperties($parser, $instance);
        $instance->continueing = $parser->isContinuing();

        return $instance;
    }

    /**
     * @return bool
     */
    public function isContinueing(): bool
    {
        return $this->continueing;
    }
}
