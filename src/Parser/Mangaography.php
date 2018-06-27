<?php

namespace Jikan\Parser;

use Jikan\Model;

/**
 * Class Animeography
 *
 * @package Jikan\Parser
 */
class Mangaography extends Ography implements ParserInterface
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
