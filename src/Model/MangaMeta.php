<?php

namespace Jikan\Model;

/**
 * Class MangaMeta
 *
 * @package Jikan\Model
 */
class MangaMeta extends ItemMeta
{
    /**
     * @param \Jikan\Parser\Common\ItemMetaParser $parser
     *
     * @return MangaMeta
     */
    public static function fromParser(\Jikan\Parser\Common\MangaMetaParser $parser): MangaMeta
    {
        $instance = new self();
        parent::setProperties($parser, $instance);

        return $instance;
    }
}
