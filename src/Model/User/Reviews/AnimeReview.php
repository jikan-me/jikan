<?php

namespace Jikan\Model\User\Reviews;

use Jikan\Parser\Anime\AnimeReviewParser;

/**
 * Class AnimeReview
 *
 * @package Jikan\Model
 */
class AnimeReview extends \Jikan\Model\Reviews\AnimeReview
{

    /**
     * Create an instance from an AnimeReviewParser parser
     *
     * @param AnimeReviewParser $parser
     *
     * @return AnimeReview
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(AnimeReviewParser $parser): AnimeReview
    {
        $instance = new self();

        $instance->malId = $parser->getId();
        $instance->url = $parser->getUrl();
        $instance->type = $parser->getType();
        $instance->helpfulCount= $parser->getHelpfulCount();
        $instance->date = $parser->getDate();
        $instance->scores = $parser->getAnimeScores();
        $instance->content = $parser->getContent();

        return $instance;
    }
}
