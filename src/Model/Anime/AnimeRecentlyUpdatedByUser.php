<?php

namespace Jikan\Model\Anime;

use Jikan\Parser\Anime\AnimeRecentlyUpdatedByUsersListParser;

/**
 * Class AnimeRecentlyUpdatedByUser
 *
 * @package Jikan\Model\Anime\AnimeRecentlyUpdatedByUser
 */
class AnimeRecentlyUpdatedByUser
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
     * @var string
     */
    private $imageUrl;

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
    private $episodesSeen;

    /**
     * @var int|null
     */
    private $episodesTotal;

    /**
     * @var \DateTimeImmutable
     */
    private $date;

    /**
     * @param AnimeRecentlyUpdatedByUsersListParser $parser
     *
     * @return self
     * @throws \Exception
     */
    public static function fromParser(AnimeRecentlyUpdatedByUsersListParser $parser): self
    {
        $instance = new self();

        $instance->username = $parser->getUsername();
        $instance->url = $parser->getUrl();
        $instance->imageUrl = $parser->getImageUrl();
        $instance->score = $parser->getScore();
        $instance->status = $parser->getStatus();
        $instance->episodesSeen = $parser->getEpisodesSeen();
        $instance->episodesTotal = $parser->getEpisodesTotal();
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
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
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
    public function getEpisodesSeen(): ?int
    {
        return $this->episodesSeen;
    }

    /**
     * @return int|null
     */
    public function getEpisodesTotal(): ?int
    {
        return $this->episodesTotal;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }
}
