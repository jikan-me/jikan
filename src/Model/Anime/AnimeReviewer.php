<?php

namespace Jikan\Model\Anime;

use Jikan\Model\Common\Reviewer;
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
    private  $episodesSeen;

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
        $instance->episodesSeen = $parser->getEpisodesSeen();
//        $instance->scores = $parser->getAnimeScores();

        return $instance;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @return int
     */
    public function getEpisodesSeen(): int
    {
        return $this->episodesSeen;
    }

    /**
     * @return AnimeReviewScores
     */
    public function getScores(): AnimeReviewScores
    {
        return $this->scores;
    }
}
