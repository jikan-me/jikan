<?php

namespace Jikan\Model\Anime;

use Jikan\Model\Common\PersonMeta;
use Jikan\Model\Resource\PersonImageResource\PersonImageResource;
use Jikan\Parser\Anime\StaffListItemParser;

/**
 * Class StaffListItem
 *
 * @package Jikan\Model
 */
class StaffListItem
{
    /**
     * @var PersonMeta
     */
    public $person;

    /**
     * @var string[]
     */
    public $positions = [];

    /**
     * @param StaffListItemParser $parser
     *
     * @return StaffListItem
     * @throws \InvalidArgumentException
     */
    public static function fromParser(StaffListItemParser $parser): self
    {
        $instance = new self();
        $instance->positions = $parser->getPositions();
        $instance->person = $parser->getPersonMeta();

        return $instance;
    }

    /**
     * @return PersonMeta
     */
    public function getPerson(): PersonMeta
    {
        return $this->person;
    }

    /**
     * @return string[]
     */
    public function getPositions(): array
    {
        return $this->positions;
    }
}
