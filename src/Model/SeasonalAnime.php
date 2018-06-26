<?php

namespace Jikan\Model;

use Jikan\Parser;

/**
 * Class AnimeCard
 *
 * @package Jikan\Model
 */
class SeasonalAnime extends AnimeCard
{
    /**
     * @var bool
     */
    private $continueing;
    /** @noinspection ReturnTypeCanBeDeclaredInspection */

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
