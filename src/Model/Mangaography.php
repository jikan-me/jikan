<?php

namespace Jikan\Model;

/**
 * Class MangaographyParser
 *
 * @package Jikan\Model
 */
class Mangaography extends Ography
{
    /**
     * @param \Jikan\Parser\Character\MangaographyParser $parser
     *
     * @return Mangaography
     */
    public static function fromParser(\Jikan\Parser\Character\MangaographyParser $parser): Mangaography
    {
        $instance = new self();
        parent::setProperties($parser, $instance);

        return $instance;
    }
}
