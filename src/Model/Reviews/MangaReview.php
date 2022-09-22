<?php

namespace Jikan\Model\Reviews;

use Jikan\Model\Manga\MangaReviewScores;

/**
 * Class UserAnimeReview
 *
 * @package Jikan\Model
 */
abstract class MangaReview extends Review
{

    /**
     * @var int|null
     */
    protected ?int $chaptersRead;


    /**
     * @return string
     */
    public function getChaptersRead(): string
    {
        return $this->chaptersRead;
    }

}
