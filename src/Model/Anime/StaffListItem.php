<?php

namespace Jikan\Model\Anime;

use Jikan\Parser\Anime\StaffListItemParser;

/**
 * Class CharacterParser
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
     * @var string
     */
    public $imageUrl;

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
        $instance->imageUrl = $parser->getImage();

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string[]
     */
    public function getPositions(): array
    {
        return $this->positions;
    }
}
