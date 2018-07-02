<?php

namespace Jikan\Model;

use Jikan\Parser;

/**
 * Class ProducerAnime
 *
 * @package Jikan\Model
 */
class ProducerAnime extends AnimeCard
{
    /**
     * @var bool
     */
    private $continuing;

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
