<?php

namespace Jikan\Model\Producer;

use Jikan\Model\Common\AnimeCard;
use Jikan\Parser;

/**
 * Class ProducerAnime
 *
 * @package Jikan\Model
 */
class ProducerAnime extends AnimeCard
{
    /**
     * @param Parser\Common\AnimeCardParser $parser
     *
     * @return ProducerAnime
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function parseProducerAnime(Parser\Common\AnimeCardParser $parser): ProducerAnime
    {
        $instance = new self();
        parent::setProperties($parser, $instance);

        return $instance;
    }
}
