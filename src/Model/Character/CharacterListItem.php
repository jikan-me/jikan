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
    private CharacterMeta $character;

    /**
     * @var string
     */
    private string $role;

    /**
     * @var int
     */
    private int $favorites;

    /**
     * @var VoiceActor[]
     */
    private array $voiceActors = [];

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
        $instance->favorites = $parser->getFavorites();

        return $instance;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return CharacterMeta
     */
    public function getCharacter(): CharacterMeta
    {
        return $this->character;
    }

    /**
     * @return VoiceActor[]
     */
    public function getVoiceActors(): array
    {
        return $this->voiceActors;
    }

    /**
     * @return int
     */
    public function getFavorites(): int
    {
        return $this->favorites;
    }
}
