<?php

namespace Jikan\Model\Character;

use Jikan\Model\Common\Ography;
use Jikan\Parser\Character\AnimeographyParser;

/**
 * Class AnimeographyParser
 *
 * @package Jikan\Model
 */
class Animeography extends Ography
{
    /**
     * @param AnimeographyParser $parser
     *
     * @return Animeography
     * @throws \InvalidArgumentException
     */
    public static function fromParser(AnimeographyParser $parser): Animeography
    {
        $instance = new self();
        parent::setProperties($parser, $instance);

        return $instance;
    }
}
