<?php

namespace Jikan\Model\User\Reviews;

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
class UserAnimeReview extends AnimeReview
{

    /**
     * @var AnimeMeta
     */
    private $entry;


    /**
     * @param AnimeReviewParser $parser
     * @return UserAnimeReview
     * @throws \Exception
     */
    public static function fromParser(AnimeReviewParser $parser): UserAnimeReview
    {
        $instance = new self();

        $instance->entry = $parser->getAnime();
        $instance->malId = $parser->getId();
        $instance->url = $parser->getUrl();
        $instance->type = $parser->getType();
        $instance->votes = $parser->getHelpfulCount();
        $instance->date = $parser->getDate();
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
     * @return AnimeMeta
     */
    public function getEntry(): AnimeMeta
    {
        return $this->entry;
    }
}
