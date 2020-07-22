<?php

namespace Jikan\Model\Manga;

use Jikan\Model\Resource\UserImageResource\UserImageResource;
use Jikan\Parser\Manga\MangaRecentlyUpdatedByUsersListParser;

/**
 * Class MangaRecentlyUpdatedByUser
 *
 * @package Jikan\Model\Manga\MangaRecentlyUpdatedByUser
 */
class MangaRecentlyUpdatedByUser
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $url;

    /**
     * @var UserImageResource
     */
    private $images;

    /**
     * @var int|null
     */
    private $score;

    /**
     * @var string
     */
    private $status;

    /**
     * @var int|null
     */
    private $volumesRead;

    /**
     * @var int|null
     */
    private $volumesTotal;

    /**
     * @var int|null
     */
    private $chaptersRead;

    /**
     * @var int|null
     */
    private $chaptersTotal;

    /**
     * @var \DateTimeImmutable
     */
    private $date;

    /**
     * @param MangaRecentlyUpdatedByUsersListParser $parser
     *
     * @return self
     * @throws \Exception
     */
    public static function fromParser(MangaRecentlyUpdatedByUsersListParser $parser): self
    {
        $instance = new self();

        $instance->username = $parser->getUsername();
        $instance->url = $parser->getUrl();
        $instance->images = UserImageResource::factory($parser->getImageUrl());
        $instance->score = $parser->getScore();
        $instance->status = $parser->getStatus();
        $instance->volumesRead = $parser->getVolumesRead();
        $instance->volumesTotal = $parser->getVolumesTotal();
        $instance->chaptersRead = $parser->getChaptersRead();
        $instance->chaptersTotal = $parser->getChaptersTotal();
        $instance->date = $parser->getDate();

        return $instance;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return UserImageResource
     */
    public function getImages(): UserImageResource
    {
        return $this->images;
    }

    /**
     * @return int|null
     */
    public function getScore(): ?int
    {
        return $this->score;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return int|null
     */
    public function getVolumesRead(): ?int
    {
        return $this->volumesRead;
    }

    /**
     * @return int|null
     */
    public function getVolumesTotal(): ?int
    {
        return $this->volumesTotal;
    }

    /**
     * @return int|null
     */
    public function getChaptersRead(): ?int
    {
        return $this->chaptersRead;
    }

    /**
     * @return int|null
     */
    public function getChaptersTotal(): ?int
    {
        return $this->chaptersTotal;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }
}
