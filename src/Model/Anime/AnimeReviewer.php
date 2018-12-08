<?php

namespace Jikan\Model\Anime;

use Jikan\Model\Common\Reviewer;
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
    private $episodesSeen;

    /**
     * @var AnimeReviewScores
     */
    private $scores;

    /**
     * @param ReviewerParser $parser
     *
     * @return AnimeReviewer
     * @throws \InvalidArgumentException
     */
    public static function fromParser(ReviewerParser $parser): AnimeReviewer
    {
        $instance = new self();

        $instance->url= $parser->getUrl();
        $instance->imageUrl = $parser->getImageUrl();
        $instance->username = $parser->getUsername();
        $instance->episodesSeen = $parser->getEpisodesSeen();
        $instance->scores = $parser->getAnimeScores();

        return $instance;
    }

}
