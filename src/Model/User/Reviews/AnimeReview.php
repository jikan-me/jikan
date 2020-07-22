<?php

namespace Jikan\Model\User\Reviews;

use Jikan\Model\Common\AnimeMeta;
use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
use Jikan\Parser\Anime\AnimeReviewParser;

/**
 * Class AnimeReview
 *
 * @package Jikan\Model
 */
class AnimeReview extends \Jikan\Model\Reviews\AnimeReview
{

    /**
     * @var string
     */
    private $title;
    /**
     * @var CommonImageResource
     */
    private $images;

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
        $instance->title = $parser->getTitle();
        $instance->images = CommonImageResource::factory($parser->getImageUrl());
        $instance->type = $parser->getType();
        $instance->votes = $parser->getHelpfulCount();
        $instance->date = $parser->getDate();
        $instance->scores = $parser->getAnimeScores();
        $instance->review = $parser->getContent();

        return $instance;
    }
}
