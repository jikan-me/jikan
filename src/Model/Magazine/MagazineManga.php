<?php

namespace Jikan\Model\Magazine;

use Jikan\Model\Common\MangaCard;
use Jikan\Parser;

/**
 * Class MagazineManga
 *
 * @package Jikan\Model
 */
class MagazineManga extends MangaCard
{
    /**
     * @param Parser\Common\MangaCardParser $parser
     *
     * @return MagazineManga
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function parseMagazineManga(Parser\Common\MangaCardParser $parser): MagazineManga
    {
        $instance = new self();
        parent::setProperties($parser, $instance);

        return $instance;
    }
}
