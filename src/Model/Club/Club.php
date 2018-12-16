<?php

namespace Jikan\Model\Club;

use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Club\ClubParser;

/**
 * Class Club
 *
 * @package Jikan\Model
 */
class Club
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
     * @var string
     */
    private $imageUrl;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $membersCount;

    /**
     * @var int
     */
    private $picturesCount;

    /**
     * @var string
     */
    private $category;

    /**
     * @var \DateTimeImmutable
     */
    private $created;

    /**
     * @var string
     */
    private $type;

    /**
     * @var MalUrl[]
     */
    private $staff;

    /**
     * @var MalUrl[]
     */
    private $animeRelations;

    /**
     * @var MalUrl[]
     */
    private $mangaRelations;

    /**
     * @var MalUrl[]
     */
    private $characterRelations;

    /**
     * @param ClubParser $parser
     * @return Club
     * @throws \Exception
     */
    public static function fromParser(ClubParser $parser): Club
    {
        $instance = new self();

        $instance->malId = $parser->getMalId();
        $instance->url = $parser->getUrl();
        $instance->title = $parser->getTitle();
        $instance->imageUrl = $parser->getImageUrl();
        $instance->membersCount = $parser->getMembersCount();
        $instance->picturesCount = $parser->getPicturesCount();
        $instance->category = $parser->getCategory();
        $instance->created = $parser->getCreated();
        $instance->animeRelations = $parser->getAnimeRelations();
        $instance->mangaRelations = $parser->getMangaRelations();
        $instance->characterRelations = $parser->getCharacterRelations();
        $instance->type = $parser->getType();
        $instance->staff = $parser->getStaff();

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
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getMembersCount(): int
    {
        return $this->membersCount;
    }

    /**
     * @return int
     */
    public function getPicturesCount(): int
    {
        return $this->picturesCount;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreated(): \DateTimeImmutable
    {
        return $this->created;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return MalUrl[]
     */
    public function getStaff(): array
    {
        return $this->staff;
    }

    /**
     * @return MalUrl[]
     */
    public function getAnimeRelations(): array
    {
        return $this->animeRelations;
    }

    /**
     * @return MalUrl[]
     */
    public function getMangaRelations(): array
    {
        return $this->mangaRelations;
    }

    /**
     * @return MalUrl[]
     */
    public function getCharacterRelations(): array
    {
        return $this->characterRelations;
    }
}
