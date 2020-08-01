<?php

namespace Jikan\Model\Anime;

use Jikan\Model\Reviews\Reviewer
use Jikan\Model\Resource\UserImageResource\UserImageResource;
use Jikan\Parser\Common\ReviewerParser;

/**
 * Class AnimeReviewScores
 *
 * @package Jikan\Model
 */
class AnimeReviewer extends Reviewer
{

    /**
     * @var int
     */
    private $episodesWatched;

    /**
     * @var AnimeReviewScores
     */
//    private $scores;


    /**
     * @param ReviewerParser $parser
     * @return AnimeReviewer
     */
    public static function fromParser(ReviewerParser $parser): AnimeReviewer
    {
        $instance = new self();

        $instance->url= $parser->getUrl();
        $instance->images = UserImageResource::factory($parser->getImageUrl());
        $instance->username = $parser->getUsername();
//        $instance->episodesWatched = $parser->getEpisodesSeen();
//        $instance->scores = $parser->getAnimeScores();

        return $instance;
    }

}
