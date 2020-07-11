<?php

namespace Jikan\Model\Anime;

use Jikan\Model\Reviews\AnimeReviewScores;
use Jikan\Parser\Anime\AnimeReviewParser;

/**
 * Class AnimeReview
 *
 * @package Jikan\Model
 */
class AnimeReview extends \Jikan\Model\Reviews\AnimeReview
{

    /**
     * @var AnimeReviewer
     */
    private $author;

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
        $instance->type = $parser->getType();
        $instance->votes = $parser->getHelpfulCount();
        $instance->date = $parser->getDate();
        $instance->author = $parser->getReviewer();
        $instance->scores = $parser->getAnimeScores();
        $instance->review = $parser->getContent();

        return $instance;
    }

    /**
     * @return AnimeReviewer
     */
    public function getAuthor(): AnimeReviewer
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getHelpfulCount(): int
    {
        return $this->helpfulCount;
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
     * @return int
     */
    public function getVotes(): int
    {
        return $this->votes;
    }

    /**
     * @return string
     */
    public function getReview(): string
    {
        return $this->review;
    }

    /**
     * @return AnimeReviewScores
     */
    public function getScores(): AnimeReviewScores
    {
        return $this->scores;
    }
}
