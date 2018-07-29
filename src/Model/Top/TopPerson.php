<?php

namespace Jikan\Model\Top;

use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Top\TopListItemParser;

/**
 * Class TopPerson
 *
 * @package Jikan\Model
 */
class TopPerson
{
    /**
     * @var int
     */
    private $rank;

    /**
     * @var \Jikan\Model\Common\MalUrl
     */
    private $malUrl;

    /**
     * @var string|null
     */
    private $nameKanji;

    /**
     * @var int
     */
    private $favorites;

    /**
     * @var string
     */
    private $imageUrl;

    /**
     * @var \DateTimeImmutable|null
     */
    private $birthday;

    /**
     * @var string|null
     */
    private $image;

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
        $instance->malUrl = $parser->getMalUrl();
        $instance->nameKanji = $parser->getKanjiName();
        $instance->favorites = $parser->getPeopleFavorites();
        $instance->image = $parser->getImage();
        $instance->birthday = $parser->getBirthday();

        return $instance;
    }

    public function __toString(): string
    {
        return $this->malUrl->getName();
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @return \Jikan\Model\Common\MalUrl
     */
    public function getMalUrl(): MalUrl
    {
        return $this->malUrl;
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
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getBirthday(): ?\DateTimeImmutable
    {
        return $this->birthday;
    }

    /**
     * @return null|string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }
}
