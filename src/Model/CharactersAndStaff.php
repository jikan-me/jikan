<?php

namespace Jikan\Model;

use Jikan\Parser\Anime\CharactersAndStaffParser;

/**
 * Class CharactersAndStaff
 *
 * @package Jikan\Model
 */
class CharactersAndStaff
{
    /**
     * @var CharacterListItem[]
     */
    private $characters;

    /**
     * @var StaffListItem[]
     */
    private $staff;

    /**
     * @param CharactersAndStaffParser $parser
     *
     * @return CharactersAndStaff
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(CharactersAndStaffParser $parser): self
    {
        $instance = new self();
        $instance->characters = $parser->getCharacters();
        $instance->staff = $parser->getStaff();

        return $instance;
    }

    /**
     * @return CharacterListItem[]
     */
    public function getCharacters(): array
    {
        return $this->characters;
    }

    /**
     * @return StaffListItem[]
     */
    public function getStaff(): array
    {
        return $this->staff;
    }
}
