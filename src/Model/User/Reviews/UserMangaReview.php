<?php

namespace Jikan\Model\User\Reviews;


use Jikan\Model\Common\MangaMeta;
use Jikan\Model\Manga\MangaReviewScores;
use Jikan\Model\Reviews\MangaReview;
use Jikan\Parser\Reviews\MangaReviewParser;

/**
 * Class UserAnimeReview
 *
 * @package Jikan\Model
 */
class UserMangaReview extends MangaReview
{

    /**
     * @var MangaMeta
     */
    private $entry;

    /**
     * @param MangaReviewParser $parser
     * @return UserAnimeReview
     * @throws \Exception
     */
    public static function fromParser(MangaReviewParser $parser): UserMangaReview
    {
        $instance = new self();

        $instance->entry = $parser->getManga();
        $instance->malId = $parser->getId();
        $instance->url = $parser->getUrl();
        $instance->type = $parser->getType();
        $instance->votes = $parser->getHelpfulCount();
        $instance->date = $parser->getDate();
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

    /**
     * @return MangaMeta
     */
    public function getEntry(): MangaMeta
    {
        return $this->entry;
    }

}
