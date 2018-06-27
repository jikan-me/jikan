<?php

namespace Jikan\Model;

use Jikan\Parser;

/**
 * Class Character
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
    public $characterUrl;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $nameKanji;

    /**
     * @var array
     */
    public $nicknames = [];

    /**
     * @var string
     */
    public $about;

    /**
     * @var int
     */
    public $memberFavorites;

    /**
     * @var string
     */
    public $imageUrl;

    /**
     * @var array
     */
    public $animeography = [];

    /**
     * @var array
     */
    public $mangaography = [];

    /**
     * @var array
     */
    public $voiceActors = [];

    /**
     * @param Parser\Character $parser
     *
     * @return Character
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\Character $parser): self
    {
        $instance = new self();
        $instance->animeography = $parser->getAnimeography();
        $instance->mangaography = $parser->getMangaography();
        $instance->voiceActors = $parser->getVoiceActors();
        $instance->malId = $parser->getMalId();
        $instance->characterUrl = $parser->getCharacterUrl();
        $instance->name = $parser->getName();
        $instance->nameKanji = $parser->getNameKanji();
        $instance->nicknames = $parser->getNameNicknames();
        $instance->about = $parser->getAbout();
        $instance->memberFavorites = $parser->getMemberFavorites();
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
    public function getNameKanji(): string
    {
        return $this->nameKanji;
    }

    /**
     * @return array
     */
    public function getNicknames(): array
    {
        return $this->nicknames;
    }

    /**
     * @return string
     */
    public function getAbout(): string
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
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return array
     */
    public function getAnimeography(): array
    {
        return $this->animeography;
    }

    /**
     * @return array
     */
    public function getMangaography(): array
    {
        return $this->mangaography;
    }

    /**
     * @return array
     */
    public function getVoiceActors(): array
    {
        return $this->voiceActors;
    }
}
