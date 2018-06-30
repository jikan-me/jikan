<?php

namespace Jikan\Model;

/**
 * Class AnimeMeta
 *
 * @package Jikan\Model
 */
class AnimeMeta extends ItemMeta
{
    /**
     * @param \Jikan\Parser\Common\ItemMetaParser $parser
     *
     * @return AnimeMeta
     */
    public static function fromParser(\Jikan\Parser\Common\AnimeMetaParser $parser): AnimeMeta
    {
        $instance = new self();
        parent::setProperties($parser, $instance);

        return $instance;
    }
}
