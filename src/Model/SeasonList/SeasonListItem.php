<?php

namespace Jikan\Model\SeasonList;

use Jikan\Parser\SeasonList\SeasonListItemParser;

/**
 * Class SeasonListItem
 *
 * @package Jikan\Model\SeasonListItem
 */
class SeasonListItem
{
    /**
     * @var int
     */
    public $year;

    /**
     * @var string[]
     */
    public $seasons;

    /**
     * @param SeasonListItemParser $parser
     *
     * @return SeasonListItem
     * @throws \InvalidArgumentException
     */
    public static function fromParser(SeasonListItemParser $parser): self
    {
        $instance = new self();
        $instance->year = $parser->getYear();
        $instance->seasons = $parser->getSeasons();

        return $instance;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @return string[]
     */
    public function getSeasons(): array
    {
        return $this->seasons;
    }
}
