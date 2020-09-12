<?php

namespace Jikan\Model\Manga;

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
     * @var int
     */
    private $malId;

    /**
     * @var string
     */
    private $url;

    /**
     * @var CharacterImageResource
     */
    private $images;

    /**
     * @var string
     */
    private $name;

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
        $instance->malId = $parser->getMalId();
        $instance->url = $parser->getCharacterUrl();
        $instance->name = $parser->getName();
        $instance->images = CharacterImageResource::factory($parser->getImage());

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
}
