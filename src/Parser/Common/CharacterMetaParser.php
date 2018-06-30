<?php

namespace Jikan\Parser\Common;

use Jikan\Model;
use Jikan\Parser\ParserInterface;

/**
 * Class CharacterMetaParser
 *
 * @package Jikan\Parser
 */
class CharacterMetaParser extends ItemMetaParser implements ParserInterface
{
    /**
     * Return the model
     *
     * @return Model\CharacterMeta
     */
    public function getModel(): Model\CharacterMeta
    {
        return Model\CharacterMeta::fromParser($this);
    }
}
