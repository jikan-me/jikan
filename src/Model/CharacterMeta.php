<?php

namespace Jikan\Model;

/**
 * Class CharacterMeta
 *
 * @package Jikan\Model
 */
class CharacterMeta extends ItemMeta
{
    /**
     * @param \Jikan\Parser\Common\ItemMetaParser $parser
     *
     * @return CharacterMeta
     */
    public static function fromParser(\Jikan\Parser\Common\CharacterMetaParser $parser): CharacterMeta
    {
        $instance = new self();
        parent::setProperties($parser, $instance);

        return $instance;
    }
}
