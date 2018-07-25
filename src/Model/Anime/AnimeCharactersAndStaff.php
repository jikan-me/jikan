<?php

namespace Jikan\Model\Anime;

use Jikan\Model\Character\CharacterListItem;
use Jikan\Parser\Anime\CharactersAndStaffParser;

/**
 * Class AnimeCharactersAndStaff
 *
 * @package Jikan\Model
 */
class AnimeCharactersAndStaff
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
     * @return AnimeCharactersAndStaff
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
     * @return \Jikan\Model\Character\CharacterListItem[]
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
