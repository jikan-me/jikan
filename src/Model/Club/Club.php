<?php

namespace Jikan\Model\Club;

use Jikan\Model\Common\MalUrl;
use Jikan\Model\Resource\ClubImageResource\ClubImageResource;
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
     * @var ClubImageResource
     */
    private $images;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $members;

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
    private $access;

    /**
     * @var MalUrl[]
     */
    private $staff;

    /**
     * @var MalUrl[]
     */
    private $anime;

    /**
     * @var MalUrl[]
     */
    private $manga;

    /**
     * @var MalUrl[]
     */
    private $characters;

    /**
     * @param  ClubParser $parser
     * @return Club
     * @throws \Exception
     */
    public static function fromParser(ClubParser $parser): Club
    {
        $instance = new self();

        $instance->malId = $parser->getMalId();
        $instance->url = $parser->getUrl();
        $instance->name = $parser->getTitle();
        $instance->images = ClubImageResource::factory($parser->getImageUrl());
        $instance->members = $parser->getMembersCount();
//        $instance->picturesCount = $parser->getPicturesCount();
        $instance->category = $parser->getCategory();
        $instance->created = $parser->getCreated();
        $instance->anime = $parser->getAnimeRelations();
        $instance->manga = $parser->getMangaRelations();
        $instance->characters = $parser->getCharacterRelations();
        $instance->access = $parser->getType();
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
     * @return ClubImageResource
     */
    public function getImages(): ClubImageResource
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
     * @return int
     */
    public function getMembers(): int
    {
        return $this->members;
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
    public function getAccess(): string
    {
        return $this->access;
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
    public function getAnime(): array
    {
        return $this->anime;
    }

    /**
     * @return MalUrl[]
     */
    public function getManga(): array
    {
        return $this->manga;
    }

    /**
     * @return MalUrl[]
     */
    public function getCharacters(): array
    {
        return $this->characters;
    }
}
