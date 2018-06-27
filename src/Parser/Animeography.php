<?php

namespace Jikan\Parser;

use Jikan\Model;

/**
 * Class Animeography
 *
 * @package Jikan\Parser
 */
class Animeography extends Ography implements ParserInterface
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
