<?php

namespace Jikan\Model\Manga;

use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
use Jikan\Model\Reviews\Reviewer;
use Jikan\Parser\Reviews\MangaReviewParser;

/**
 * Class UserMangaReview
 *
 * @package Jikan\Model
 */
class MangaReview extends \Jikan\Model\Reviews\MangaReview
{
    /**
     * @var Reviewer
     */
    private Reviewer $user;


    /**
     * @param MangaReviewParser $parser
     * @return MangaReview
     * @throws \Exception
     */
    public static function fromParser(MangaReviewParser $parser): MangaReview
    {
        $instance = new self();

        $instance->malId = $parser->getId();
        $instance->url = $parser->getUrl();
        $instance->type = $parser->getType() ?? 'manga';
        $instance->reactions = $parser->getReactions();
        $instance->date = $parser->getDate();
        $instance->user = $parser->getReviewer();
        $instance->score = $parser->getReviewerScore();
        $instance->review = $parser->getContent();
        $instance->tags = $parser->getReviewTag();
        $instance->isPreliminary = $parser->isPreliminary();
        $instance->chaptersRead = $parser->getChaptersRead();
        $instance->isSpoiler = $parser->isSpoiler();

        return $instance;
    }

    /**
     * @return Reviewer
     */
    public function getUser(): Reviewer
    {
        return $this->user;
    }
}
