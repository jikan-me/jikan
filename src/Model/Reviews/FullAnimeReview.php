<?php

namespace Jikan\Model\Reviews;

use Jikan\Model\Anime\AnimeReviewScores;
use Jikan\Model\Common\AnimeMeta;
use Jikan\Parser\Reviews\AnimeReviewParser;

/**
 * Class UserAnimeReview
 *
 * @package Jikan\Model
 */
class FullAnimeReview extends AnimeReview
{
    /**
     * @var AnimeMeta
     */
    private AnimeMeta $entry;

    /**
     * @var Reviewer
     */
    private Reviewer $user;

    /**
     * @param AnimeReviewParser $parser
     * @return FullAnimeReview
     * @throws \Exception
     */
    public static function fromParser(AnimeReviewParser $parser): FullAnimeReview
    {
        $instance = new self();

        $instance->entry = $parser->getAnime();
        $instance->malId = $parser->getId();
        $instance->url = $parser->getUrl();
        $instance->type = $parser->getType();
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
}
