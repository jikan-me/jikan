<?php

namespace Jikan\Model\Reviews;

use Jikan\Model\Anime\AnimeReviewScores;
use Jikan\Model\Manga\MangaReviewScores;

/**
 * Class Review
 *
 * @package Jikan\Model
 */
abstract class Review
{
    /**
     * @var int
     */
    protected int $malId;

    /**
     * @var string
     */
    protected string $url;

    /**
     * @var string|null
     */
    protected ?string $type;

    /**
     * @var Reactions
     */
    protected Reactions $reactions;

    /**
     * @var \DateTimeImmutable
     */
    protected \DateTimeImmutable $date;

    /**
     * @var string
     */
    protected string $review;

    /**
     * @var int
     */
    protected int $score;

    /**
     * @var array
     */
    protected array $tags;

    /**
     * @var bool
     */
    protected bool $isSpoiler;

    /**
     * @var bool
     */
    protected bool $isPreliminary;

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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return Reactions
     */
    public function getReactions(): Reactions
    {
        return $this->reactions;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getReview(): string
    {
        return $this->review;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return bool
     */
    public function isSpoiler(): bool
    {
        return $this->isSpoiler;
    }

    /**
     * @return bool
     */
    public function isPreliminary(): bool
    {
        return $this->isPreliminary;
    }
}
