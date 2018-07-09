<?php

namespace Jikan\Model\Character;

use Jikan\Model\Common\Ography;
use Jikan\Parser\Character\MangaographyParser;

/**
 * Class MangaographyParser
 *
 * @package Jikan\Model
 */
class Mangaography extends Ography
{
    /**
     * @param MangaographyParser $parser
     *
     * @return Mangaography
     * @throws \InvalidArgumentException
     */
    public static function fromParser(MangaographyParser $parser): Mangaography
    {
        $instance = new self();
        parent::setProperties($parser, $instance);

        return $instance;
    }
}
