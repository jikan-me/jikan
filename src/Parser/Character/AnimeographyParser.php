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
     * @return \Jikan\Model\Character\Animeography
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Character\Animeography
    {
        return Model\Character\Animeography::fromParser($this);
    }
}
