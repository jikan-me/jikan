<?php

namespace Jikan\Model\Top;

use Jikan\Model\Resource\PersonImageResource\PersonImageResource;
use Jikan\Parser\Top\TopListItemParser;

/**
 * Class TopPersonListItem
 *
 * @package Jikan\Model
 */
class TopPersonListItem
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
     * @var int
     */
    private $favorites;

    /**
     * @var PersonImageResource
     */
    private $images;

    /**
     * @var \DateTimeImmutable|null
     */
    private $birthday;

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
        $instance->favorites = $parser->getPeopleFavorites();
        $instance->images = PersonImageResource::factory($parser->getImage());
        $instance->birthday = $parser->getBirthday();

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
     * @return int
     */
    public function getFavorites(): int
    {
        return $this->favorites;
    }

    /**
     * @return PersonImageResource
     */
    public function getImages(): PersonImageResource
    {
        return $this->images;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getBirthday(): ?\DateTimeImmutable
    {
        return $this->birthday;
    }
}
