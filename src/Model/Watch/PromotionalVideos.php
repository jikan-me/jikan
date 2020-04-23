<?php

namespace Jikan\Model\Watch;

use Jikan\Model\Common\Collection\Results;
use Jikan\Parser\Watch\WatchPromotionalVideosParser;

/**
 * Class PromotionalVideos
 *
 * @package Jikan\Model
 */
class PromotionalVideos extends Results
{
    /**
     * @param WatchPromotionalVideosParser $parser
     *
     * @return PromotionalVideos
     * @throws \Exception
     */
    public static function fromParser(WatchPromotionalVideosParser $parser): PromotionalVideos
    {
        $instance = new self();
        $instance->results = $parser->getResults();

        return $instance;
    }
}
