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
     * @return \Jikan\Model\Character\Mangaography
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Character\Mangaography
    {
        return Model\Character\Mangaography::fromParser($this);
    }
}
