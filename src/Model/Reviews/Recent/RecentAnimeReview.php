<?php

namespace Jikan\Model\Reviews\Recent;

use Jikan\Model\Anime\AnimeReviewScores;
use Jikan\Model\Common\AnimeMeta;
use Jikan\Model\Reviews\AnimeReview;
use Jikan\Model\Reviews\Reviewer;
use Jikan\Parser\Reviews\AnimeReviewParser;

/**
 * Class UserAnimeReview
 *
 * @package Jikan\Model
 */
class RecentAnimeReview extends AnimeReview
{
    /**
     * @var AnimeMeta
     */
    private $entry;

    /**
     * @var Reviewer
     */
    private $user;

    /**
     * @param AnimeReviewParser $parser
     * @return RecentAnimeReview
     * @throws \Exception
     */
    public static function fromParser(AnimeReviewParser $parser): RecentAnimeReview
    {
        $instance = new self();

        $instance->entry = $parser->getAnime();
        $instance->malId = $parser->getId();
        $instance->url = $parser->getUrl();
        $instance->type = $parser->getType();
        $instance->votes = $parser->getHelpfulCount();
        $instance->date = $parser->getDate();
        $instance->user = $parser->getReviewer();
        $instance->scores = $parser->getAnimeScores();
        $instance->review = $parser->getContent();
        $instance->episodesWatched = $parser->getEpisodesWatched();

        return $instance;
    }

    /**
     * @return string
     */
    public function getEpisodesWatched(): string
    {
        return $this->episodesWatched;
    }

    /**
     * @return AnimeReviewScores
     */
    public function getScores(): AnimeReviewScores
    {
        return $this->scores;
    }

    /**
     * @return AnimeMeta
     */
    public function getEntry(): AnimeMeta
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
