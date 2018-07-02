<?php

namespace Jikan\Model;

use Jikan\Parser;

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
     * @param \Jikan\Parser\Anime\StaffListItemParser $parser
     *
     * @return StaffListItem
     */
    public static function fromParser(Parser\Anime\StaffListItemParser $parser): self
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
