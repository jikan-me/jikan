<?php

namespace Jikan\Parser\Common;

use Jikan\Model;
use Jikan\Parser\ParserInterface;

/**
 * Class AnimeMetaParser
 *
 * @package Jikan\Parser
 */
class AnimeMetaParser extends ItemMetaParser implements ParserInterface
{
    /**
     * Return the model
     *
     * @return Model\AnimeMeta
     */
    public function getModel(): Model\AnimeMeta
    {
        return Model\AnimeMeta::fromParser($this);
    }
}
