<?php

namespace Jikan\Model\Anime;

use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
use Jikan\Model\Reviews\Reviewer;
use Jikan\Parser\Reviews\AnimeReviewParser;

/**
 * Class UserAnimeReview
 *
 * @package Jikan\Model
 */
class AnimeReview extends \Jikan\Model\Reviews\AnimeReview
{
    /**
     * @var Reviewer
     */
    private $user;

    /**
     * @param AnimeReviewParser $parser
     * @return AnimeReview
     * @throws \Exception
     */
    public static function fromParser(AnimeReviewParser $parser): AnimeReview
    {
        $instance = new self();

        $instance->malId = $parser->getId();
        $instance->url = $parser->getUrl();
        $instance->type = $parser->getType() ?? 'anime';
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
