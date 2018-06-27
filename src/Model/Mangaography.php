<?php

namespace Jikan\Model;

/**
 * Class Mangaography
 *
 * @package Jikan\Model
 */
class Mangaography extends Ography
{
    /**
     * @param \Jikan\Parser\Mangaography $parser
     *
     * @return Mangaography
     */
    public static function fromParser(\Jikan\Parser\Mangaography $parser): Mangaography
    {
        $instance = new self();
        parent::setProperties($parser, $instance);

        return $instance;
    }
}
