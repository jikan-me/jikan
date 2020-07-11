<?php

namespace Jikan\Model\Top;

use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Top\TopListItemParser;

/**
 * Class TopCharacter
 *
 * @package Jikan\Model
 */
class TopCharacter
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
     * @var string
     */
    private $imageUrl;

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
        $instance->imageUrl = $parser->getImage();

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
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
}
