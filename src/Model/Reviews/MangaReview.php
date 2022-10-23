<?php

namespace Jikan\Model\Reviews;

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
    public function getChaptersRead(): ?string
    {
        return $this->chaptersRead;
    }
}
