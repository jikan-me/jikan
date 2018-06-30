<?php

namespace Jikan\Parser\Common;

use Jikan\Model;
use Jikan\Parser\ParserInterface;

/**
 * Class MangaMetaParser
 *
 * @package Jikan\Parser
 */
class MangaMetaParser extends ItemMetaParser implements ParserInterface
{
    /**
     * Return the model
     *
     * @return Model\MangaMeta
     */
    public function getModel(): Model\MangaMeta
    {
        return Model\MangaMeta::fromParser($this);
    }
}
