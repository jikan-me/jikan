<?php

namespace Jikan\Parser\Character;

use Jikan\Model;
use Jikan\Parser\ParserInterface;

/**
 * Class AnimeographyParser
 *
 * @package Jikan\Parser
 */
class MangaographyParser extends OgraphyParser implements ParserInterface
{
    /**
     * Return the model
     *
     * @return Model\Mangaography
     */
    public function getModel(): Model\Mangaography
    {
        return Model\Mangaography::fromParser($this);
    }
}
