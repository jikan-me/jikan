<?php

namespace Jikan\Model\Reviews;

/**
 * Class AnimeReview
 *
 * @package Jikan\Model
 */
abstract class AnimeReview
{

    /**
     * @var int
     */
    protected $malId;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $helpfulCount;

    /**
     * @var \DateTimeImmutable
     */
    protected $date;

    /**
     * @var AnimeReviewScores
     */
    protected $scores;

    /**
     * @var string
     */
    protected $content;

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
     * @return int
     */
    public function getHelpfulCount(): int
    {
        return $this->helpfulCount;
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
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
