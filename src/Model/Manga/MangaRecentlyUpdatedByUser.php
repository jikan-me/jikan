<?php

namespace Jikan\Model\Manga;

use Jikan\Model\Common\UserMeta;
use Jikan\Parser\Manga\MangaRecentlyUpdatedByUsersListParser;

/**
 * Class MangaRecentlyUpdatedByUser
 *
 * @package Jikan\Model\Manga\MangaRecentlyUpdatedByUser
 */
class MangaRecentlyUpdatedByUser
{
    /**
     * @var UserMeta
     */
    private $user;

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

        $instance->user = $parser->getUserMeta();
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
     * @return UserMeta
     */
    public function getUser(): UserMeta
    {
        return $this->user;
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
