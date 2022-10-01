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
    private Reviewer $user;

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
        $instance->reactions = $parser->getReactions();
        $instance->date = $parser->getDate();
        $instance->user = $parser->getReviewer();
        $instance->score = $parser->getReviewerScore();
        $instance->review = $parser->getContent();
        $instance->tags = $parser->getReviewTag();
        $instance->isPreliminary = $parser->isPreliminary();
        $instance->episodesWatched = $parser->getEpisodesWatched();
        $instance->isSpoiler = $parser->isSpoiler();

        return $instance;
    }

    /**
     * @return Reviewer
     */
    public function getUser(): Reviewer
    {
        return $this->user;
    }
}
