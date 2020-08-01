<?php

namespace Jikan\Model\Reviews\Recent;

use Jikan\Model\Common\MangaMeta;
use Jikan\Model\Reviews\MangaReview;
use Jikan\Model\Reviews\Reviewer;
use Jikan\Parser\Reviews\MangaReviewParser;

/**
 * Class MangaReview
 *
 * @package Jikan\Model
 */
class RecentMangaReview extends MangaReview
{
    /**
     * @var MangaMeta
     */
    private $manga;

    /**
     * @var Reviewer
     */
    private $user;

    /**
     * Create an instance from an MangaReviewParser parser
     *
     * @param MangaReviewParser $parser
     *
     * @return RecentMangaReview
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(MangaReviewParser $parser): RecentMangaReview
    {
        $instance = new self();

        $instance->manga = $parser->getManga();
        $instance->malId = $parser->getId();
        $instance->url = $parser->getUrl();
        $instance->type = $parser->getType();
        $instance->votes = $parser->getHelpfulCount();
        $instance->date = $parser->getDate();
        $instance->user = $parser->getReviewer();
        $instance->scores = $parser->getMangaScores();
        $instance->review = $parser->getContent();
        $instance->chaptersRead = $parser->getChaptersRead();

        return $instance;
    }

}
