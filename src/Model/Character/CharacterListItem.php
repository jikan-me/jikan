<?php

namespace Jikan\Model\Character;

use Jikan\Model\Common\CharacterMeta;
use Jikan\Model\Resource\CharacterImageResource\CharacterImageResource;
use Jikan\Parser\Character\CharacterListItemParser;

/**
 * Class CharacterParser
 *
 * @package Jikan\Model
 */
class CharacterListItem
{
    /**
     * @var CharacterMeta
     */
    public $character;

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
        $instance->character = $parser->getCharacterMeta();
        $instance->role = $parser->getRole();

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
     * @return CharacterImageResource
     */
    public function getImages(): CharacterImageResource
    {
        return $this->images;
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
     * @return VoiceActor[]
     */
    public function getVoiceActors(): array
    {
        return $this->voiceActors;
    }

}
