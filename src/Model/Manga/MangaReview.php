<?php

namespace Jikan\Model\Manga;

use Jikan\Parser\Manga\MangaReviewParser;

/**
 * Class MangaReview
 *
 * @package Jikan\Model
 */
class MangaReview extends \Jikan\Model\Reviews\MangaReview
{
    /**
     * @var MangaReviewer
     */
    private $reviewer;

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
        $instance->reviewer = $parser->getReviewer();
        $instance->scores = $parser->getMangaScores();
        $instance->content = $parser->getContent();

        return $instance;
    }

    /**
     * @return MangaReviewer
     */
    public function getReviewer(): MangaReviewer
    {
        return $this->reviewer;
    }
}
