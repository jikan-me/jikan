<?php

namespace Jikan\Model;

/**
 * Class AnimeographyParser
 *
 * @package Jikan\Model
 */
class Animeography extends Ography
{
    /**
     * @param \Jikan\Parser\Character\AnimeographyParser $parser
     *
     * @return Animeography
     */
    public static function fromParser(\Jikan\Parser\Character\AnimeographyParser $parser): Animeography
    {
        $instance = new self();
        parent::setProperties($parser, $instance);

        return $instance;
    }
}
