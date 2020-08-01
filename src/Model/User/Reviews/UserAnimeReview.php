<?php

namespace Jikan\Model\User\Reviews;

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
    private $anime;


    /**
     * @param AnimeReviewParser $parser
     * @return UserAnimeReview
     * @throws \Exception
     */
    public static function fromParser(AnimeReviewParser $parser): UserAnimeReview
    {
        $instance = new self();

        $instance->anime = $parser->getAnime();
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
}
