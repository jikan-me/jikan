<?php

namespace Jikan\Model\Manga;

use Jikan\Parser\Anime\CharacterListItemParser;

/**
 * Class CharacterParser
 *
 * @package Jikan\Model\Manga
 */
class CharacterListItem
{
    /**
     * @var int
     */
    private $malId;

    /**
     * @var string
     */
    private $characterUrl;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $imageUrl;

    /**
     * @var string
     */
    private $role;

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @param CharacterListItemParser $parser
     *
     * @return CharacterListItem
     */
    public static function fromParser(CharacterListItemParser $parser): self
    {
        $instance = new self();
        $instance->role = $parser->getRole();
        $instance->malId = $parser->getMalId();
        $instance->characterUrl = $parser->getCharacterUrl();
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
    public function getCharacterUrl(): string
    {
        return $this->characterUrl;
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
    public function getRole(): string
    {
        return $this->role;
    }
}
