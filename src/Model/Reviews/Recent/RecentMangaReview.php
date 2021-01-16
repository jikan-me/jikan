<?php

namespace Jikan\Model\Reviews\Recent;

use Jikan\Model\Common\MangaMeta;
use Jikan\Model\Manga\MangaReviewScores;
use Jikan\Model\Reviews\MangaReview;
use Jikan\Model\Reviews\Reviewer;
use Jikan\Parser\Reviews\MangaReviewParser;

/**
 * Class UserMangaReview
 *
 * @package Jikan\Model
 */
class RecentMangaReview extends MangaReview
{
    /**
     * @var MangaMeta
     */
    private $entry;

    /**
     * @var Reviewer
     */
    private $user;

    /**
     * Create an instance from an MangaReviewParser parser
     *
     * @param MangaReviewParser $parser
     *
     * @return RecentMangaReview
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(MangaReviewParser $parser): RecentMangaReview
    {
        $instance = new self();

        $instance->manga = $parser->getManga();
        $instance->malId = $parser->getId();
        $instance->url = $parser->getUrl();
        $instance->type = $parser->getType();
        $instance->votes = $parser->getHelpfulCount();
        $instance->date = $parser->getDate();
        $instance->user = $parser->getReviewer();
        $instance->scores = $parser->getMangaScores();
        $instance->review = $parser->getContent();
        $instance->chaptersRead = $parser->getChaptersRead();

        return $instance;
    }

    /**
     * @return string
     */
    public function getChaptersRead(): string
    {
        return $this->chaptersRead;
    }

    /**
     * @return MangaReviewScores
     */
    public function getScores(): MangaReviewScores
    {
        return $this->scores;
    }

    /**
     * @return MangaMeta
     */
    public function getEntry(): MangaMeta
    {
        return $this->entry;
    }

    /**
     * @return Reviewer
     */
    public function getUser(): Reviewer
    {
        return $this->user;
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getVotes(): int
    {
        return $this->votes;
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
}
