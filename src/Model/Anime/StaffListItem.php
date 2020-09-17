<?php

namespace Jikan\Model\Anime;

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
     * @var int
     */
    public $malId;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $name;

    /**
     * @var PersonImageResource
     */
    public $images;

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
        $instance->malId = $parser->getMalId();
        $instance->url = $parser->getUrl();
        $instance->name = $parser->getName();
        $instance->images = PersonImageResource::factory($parser->getImage());

        return $instance;
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        return $this->malId;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return PersonImageResource
     */
    public function getImages(): PersonImageResource
    {
        return $this->images;
    }

    /**
     * @return string[]
     */
    public function getPositions(): array
    {
        return $this->positions;
    }

}
