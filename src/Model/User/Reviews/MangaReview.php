<?php

namespace Jikan\Model\User\Reviews;

use Jikan\Parser\Manga\MangaReviewParser;

/**
 * Class MangaReview
 *
 * @package Jikan\Model
 */
class MangaReview extends \Jikan\Model\Reviews\MangaReview
{
    /**
     * Create an instance from an MangaReviewParser parser
     *
     * @param MangaReviewParser $parser
     *
     * @return MangaReview
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(MangaReviewParser $parser): MangaReview
    {
        $instance = new self();

        $instance->malId = $parser->getId();
        $instance->url = $parser->getUrl();
        $instance->type = $parser->getType();
        $instance->helpfulCount= $parser->getHelpfulCount();
        $instance->date = $parser->getDate();
        $instance->scores = $parser->getMangaScores();
        $instance->content = $parser->getContent();

        return $instance;
    }
}
