<?php

namespace Jikan\Model\Anime;

use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
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
    private $reviewer;

    /**
     * @var CommonImageResource
     */
    private $images;

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
        $instance->images = CommonImageResource::factory($parser->getImageUrl());
        $instance->type = $parser->getType();
        $instance->votes = $parser->getHelpfulCount();
        $instance->date = $parser->getDate();
        $instance->reviewer = $parser->getReviewer();
        $instance->scores = $parser->getAnimeScores();
        $instance->review = $parser->getContent();

        return $instance;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return CommonImageResource
     */
    public function getImages(): CommonImageResource
    {
        return $this->images;
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
     * @return string
     */
    public function getReview(): string
    {
        return $this->review;
    }

    /**
     * @return AnimeReviewer
     */
    public function getReviewer(): AnimeReviewer
    {
        return $this->reviewer;
    }

    /**
     * @return int
     */
    public function getVotes(): int
    {
        return $this->votes;
    }

    /**
     * @return AnimeReviewScores
     */
    public function getScores(): AnimeReviewScores
    {
        return $this->scores;
    }
}
