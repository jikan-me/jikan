<?php

namespace Jikan\Model;

/**
 * Class Animeography
 *
 * @package Jikan\Model
 */
class Animeography extends Ography
{
    /**
     * @param \Jikan\Parser\Animeography $parser
     *
     * @return Animeography
     */
    public static function fromParser(\Jikan\Parser\Animeography $parser): Animeography
    {
        $instance = new self();
        parent::setProperties($parser, $instance);

        return $instance;
    }
}
