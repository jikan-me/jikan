<?php

namespace Jikan\Model\User\Reviews;

use Jikan\Model\Common\MangaMeta;
use Jikan\Model\Manga\MangaReviewScores;
use Jikan\Model\Reviews\MangaReview;
use Jikan\Parser\Reviews\MangaReviewParser;

/**
 * Class UserAnimeReview
 *
 * @package Jikan\Model
 */
class UserMangaReview extends MangaReview
{
    /**
     * @var MangaMeta
     */
    private MangaMeta $entry;

    /**
     * @param MangaReviewParser $parser
     * @return UserAnimeReview
     * @throws \Exception
     */
    public static function fromParser(MangaReviewParser $parser): UserMangaReview
    {
        $instance = new self();

        $instance->entry = $parser->getManga('user');
        $instance->malId = $parser->getId();
        $instance->url = $parser->getUrl();
        $instance->type = $parser->getType();
        $instance->reactions = $parser->getReactions();
        $instance->date = $parser->getDate();
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
}
