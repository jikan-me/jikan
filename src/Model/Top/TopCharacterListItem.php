<?php

namespace Jikan\Model\Top;

use Jikan\Model\Common\MalUrl;
use Jikan\Model\Resource\CharacterImageResource\CharacterImageResource;
use Jikan\Parser\Top\TopListItemParser;

/**
 * Class TopCharacterListItem
 *
 * @package Jikan\Model
 */
class TopCharacterListItem
{
    /**
     * @var int
     */
    private $malId;

    /**
     * @var int
     */
    private $rank;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $url;

    /**
     * @var CharacterImageResource
     */
    private $images;

    /**
     * @var string|null
     */
    private $nameKanji;

    /**
     * @var \Jikan\Model\Common\MalUrl[]
     */
    private $animeography;

    /**
     * @var MalUrl[]
     */
    private $mangaography;

    /**
     * @var int
     */
    private $favorites;

    /**
     * Create an instance from an AnimeParser parser
     *
     * @param TopListItemParser $parser
     *
     * @return self
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(TopListItemParser $parser): self
    {
        $instance = new self();
        $instance->rank = $parser->getRank();
        $instance->malId = $parser->getMalUrl()->getMalId();
        $instance->title = $parser->getMalUrl()->getTitle();
        $instance->url = $parser->getMalUrl()->getUrl();
        $instance->nameKanji = $parser->getKanjiName();
        $instance->animeography = $parser->getAnimeography();
        $instance->mangaography = $parser->getMangaography();
        $instance->favorites = $parser->getFavorites();
        $instance->images = CharacterImageResource::factory($parser->getImage());

        return $instance;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getTitle();
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string|null
     */
    public function getNameKanji(): ?string
    {
        return $this->nameKanji;
    }

    /**
     * @return \Jikan\Model\Common\MalUrl[]
     */
    public function getAnimeography(): array
    {
        return $this->animeography;
    }

    /**
     * @return MalUrl[]
     */
    public function getMangaography(): array
    {
        return $this->mangaography;
    }

    /**
     * @return int
     */
    public function getFavorites(): int
    {
        return $this->favorites;
    }

    /**
     * @return CharacterImageResource
     */
    public function getImages(): CharacterImageResource
    {
        return $this->images;
    }
}
