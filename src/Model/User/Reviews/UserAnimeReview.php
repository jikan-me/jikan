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
    private AnimeMeta $entry;

    /**
     * @param AnimeReviewParser $parser
     * @return UserAnimeReview
     * @throws \Exception
     */
    public static function fromParser(AnimeReviewParser $parser): UserAnimeReview
    {
        $instance = new self();

        $instance->entry = $parser->getAnime('user');
        $instance->malId = $parser->getId();
        $instance->url = $parser->getUrl();
        $instance->type = $parser->getType();
        $instance->reactions = $parser->getReactions();
        $instance->date = $parser->getDate();
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
}
