<?php

namespace Jikan\Model\Manga;

use Jikan\Model\Common\CharacterMeta;
use Jikan\Model\Resource\CharacterImageResource\CharacterImageResource;
use Jikan\Parser\Character\CharacterListItemParser;

/**
 * Class CharacterParser
 *
 * @package Jikan\Model\Manga\Manga
 */
class CharacterListItem
{
    /**
     * @var CharacterMeta
     */
    private $character;

    /**
     * @var string
     */
    private $role;

    /**
     * @param CharacterListItemParser $parser
     *
     * @return CharacterListItem
     * @throws \InvalidArgumentException
     */
    public static function fromParser(CharacterListItemParser $parser): self
    {
        $instance = new self();
        $instance->role = $parser->getRole();
        $instance->character = $parser->getCharacterMeta();

        return $instance;
    }

    /**
     * @return CharacterMeta
     */
    public function getCharacter(): CharacterMeta
    {
        return $this->character;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }
}
