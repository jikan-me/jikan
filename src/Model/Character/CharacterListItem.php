<?php

namespace Jikan\Model\Character;

use Jikan\Parser\Character\CharacterListItemParser;

/**
 * Class CharacterParser
 *
 * @package Jikan\Model
 */
class CharacterListItem
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
    public $imageUrl;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $role;

    /**
     * @var VoiceActor[]
     */
    public $voiceActors = [];

    /**
     * @param CharacterListItemParser $parser
     *
     * @return CharacterListItem
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(CharacterListItemParser $parser): self
    {
        $instance = new self();
        $instance->voiceActors = $parser->getVoiceActors();
        $instance->malId = $parser->getMalId();
        $instance->url = $parser->getCharacterUrl();
        $instance->name = $parser->getName();
        $instance->role = $parser->getRole();
        $instance->imageUrl = $parser->getImage();

        return $instance;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
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
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return VoiceActor[]
     */
    public function getVoiceActors(): array
    {
        return $this->voiceActors;
    }
}
