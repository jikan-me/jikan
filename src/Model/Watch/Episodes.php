<?php

namespace Jikan\Model\Watch;

use Jikan\Model\Common\Collection\Results;
use Jikan\Parser\Watch\WatchEpisodesParser;

/**
 * Class Episodes
 *
 * @package Jikan\Model
 */
class Episodes extends Results
{
    /**
     * @param WatchEpisodesParser $parser
     *
     * @return Episodes
     * @throws \Exception
     */
    public static function fromParser(WatchEpisodesParser $parser): Episodes
    {
        $instance = new self();
        $instance->results = $parser->getResults();

        return $instance;
    }
}
