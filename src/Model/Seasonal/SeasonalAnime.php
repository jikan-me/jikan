<?php

namespace Jikan\Model\Seasonal;

use Jikan\Model\Common\AnimeCard;
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
    protected $continuing;

    /**
     * @param Parser\Common\AnimeCardParser $parser
     *
     * @return SeasonalAnime
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function parseSeasonalAnime(Parser\Common\AnimeCardParser $parser): SeasonalAnime
    {
        $instance = new self();
        parent::setProperties($parser, $instance);
        $instance->continuing = $parser->isContinuing();

        return $instance;
    }

    /**
     * @return bool
     */
    public function isContinuing(): bool
    {
        return $this->continuing;
    }
}
