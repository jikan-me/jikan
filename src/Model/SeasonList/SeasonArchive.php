<?php

namespace Jikan\Model\SeasonList;

use Jikan\Model\Common\Collection\Results;
use Jikan\Parser\SeasonList\SeasonListParser;

/**
 * Class SeasonArchive
 * @package Jikan\Model\SeasonList
 */
class SeasonArchive extends Results
{
    /**
     * @param SeasonListParser $parser
     * @return static
     */
    public static function fromParser(SeasonListParser $parser): self
    {
        $instance = new self();
        $instance->results = $parser->getResults();

        return $instance;
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }
}
