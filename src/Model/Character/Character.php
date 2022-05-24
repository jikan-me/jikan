<?php

namespace Jikan\Model\Character;

use Jikan\Model\Resource\CharacterImageResource\CharacterImageResource;
use Jikan\Parser;

/**
 * Class CharacterParser
 *
 * @package Jikan\Model
 */
class Character
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
     * @var string|null
     */
    public $nameKanji;

    /**
     * @var string[]
     */
    public $nicknames = [];

    /**
     * @var string|null
     */
    public $about;

    /**
     * @var int
     */
    public $memberFavorites;

    /**
     * @var CharacterImageResource
     */
    public $images;

    /**
     * @var Animeography[]
     */
    public $animeography = [];

    /**
     * @var Mangaography[]
     */
    public $mangaography = [];

    /**
     * @var VoiceActor[]
     */
    public $voiceActors = [];

    /**
     * @param Parser\Character\CharacterParser $parser
     *
     * @return Character
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\Character\CharacterParser $parser): self
    {
        $instance = new self();
        $instance->animeography = $parser->getAnimeography();
        $instance->mangaography = $parser->getMangaography();
        $instance->voiceActors = $parser->getVoiceActors();
        $instance->malId = $parser->getMalId();
        $instance->url = $parser->getCharacterUrl();
        $instance->name = $parser->getName();
        $instance->nameKanji = $parser->getNameKanji();
        $instance->nicknames = $parser->getNameNicknames();
        $instance->about = $parser->getAbout();
        $instance->memberFavorites = $parser->getMemberFavorites();
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getNameKanji(): ?string
    {
        return $this->nameKanji;
    }

    /**
     * @return string[]
     */
    public function getNicknames(): array
    {
        return $this->nicknames;
    }

    /**
     * @return string|null
     */
    public function getAbout(): ?string
    {
        return $this->about;
    }

    /**
     * @return int
     */
    public function getMemberFavorites(): int
    {
        return $this->memberFavorites;
    }

    /**
     * @return CharacterImageResource
     */
    public function getImages(): CharacterImageResource
    {
        return $this->images;
    }

    /**
     * @return Animeography[]
     */
    public function getAnimeography(): array
    {
        return $this->animeography;
    }

    /**
     * @return Mangaography[]
     */
    public function getMangaography(): array
    {
        return $this->mangaography;
    }

    /**
     * @return VoiceActor[]
     */
    public function getVoiceActors(): array
    {
        return $this->voiceActors;
    }
}
