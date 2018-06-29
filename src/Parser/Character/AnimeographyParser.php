<?php

namespace Jikan\Parser\Character;

use Jikan\Model;
use Jikan\Parser\ParserInterface;

/**
 * Class AnimeographyParser
 *
 * @package Jikan\Parser
 */
class AnimeographyParser extends OgraphyParser implements ParserInterface
{
    /**
     * Return the model
     *
     * @return Model\Animeography
     */
    public function getModel(): Model\Animeography
    {
        return Model\Animeography::fromParser($this);
    }
}
