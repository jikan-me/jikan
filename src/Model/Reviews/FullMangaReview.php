<?php

namespace Jikan\Model\Reviews;

use Jikan\Model\Common\MangaMeta;
use Jikan\Model\Manga\MangaReviewScores;
use Jikan\Parser\Reviews\MangaReviewParser;

/**
 * Class UserMangaReview
 *
 * @package Jikan\Model
 */
class FullMangaReview extends MangaReview
{
    /**
     * @var MangaMeta
     */
    private MangaMeta $entry;

    /**
     * @var Reviewer
     */
    private Reviewer $user;

    /**
     * Create an instance from an MangaReviewParser parser
     *
     * @param MangaReviewParser $parser
     *
     * @return FullMangaReview
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(MangaReviewParser $parser): FullMangaReview
    {
        $instance = new self();

        $instance->entry = $parser->getManga();
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
     * @return MangaMeta
     */
    public function getEntry(): MangaMeta
    {
        return $this->entry;
    }

    /**
     * @return Reviewer
     */
    public function getUser(): Reviewer
    {
        return $this->user;
    }
}
