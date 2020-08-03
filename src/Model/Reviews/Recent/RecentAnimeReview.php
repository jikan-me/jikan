<?php

namespace Jikan\Model\Reviews\Recent;

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
    private $anime;

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

        $instance->anime = $parser->getAnime();
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

}
